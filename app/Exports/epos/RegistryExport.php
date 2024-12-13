<?php
namespace App\Exports\epos;

use App\Models\Keyword;
use App\Models\Vocabulary;
use EasyRdf\Graph;
use App\Models\LaboratoryOrganization;
use EasyRdf\Resource;
use EasyRdf;
use EasyRdf\RdfNamespace;
use App\Models\LaboratoryContactPerson;
use App\Models\Laboratory;
use EasyRdf\Literal;
use App\Models\LaboratoryEquipment;

class RegistryExport
{
    public $organizations;
    
    public function __construct($organizations) {
        $this->organizations = $organizations;
    }
    
    public function export($type = 'turtle')
    {        
        $graph = new Graph();
        
        // Set namespaces
        RdfNamespace::delete('dc');
        RdfNamespace::delete('dcterms');
        RdfNamespace::set('dct', 'http://purl.org/dc/terms/');
        RdfNamespace::set('epos', 'https://www.epos-eu.org/epos-dcat-ap#');       
        RdfNamespace::set('locn', 'http://www.w3.org/ns/locn#');
        RdfNamespace::set('gsp', 'http://www.opengis.net/ont/geosparql#');
        RdfNamespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
        
        foreach ($this->organizations as $organization) {
            // Add organization to graph
            $organizationGraph = $graph->resource($organization->external_identifier, 'schema:Organization');
            $schemaIdentifier = $graph->newBnode('schema:PropertyValue');
            $schemaIdentifier->set('schema:propertyID', "ROR");


            $schemaIdentifier->set('schema:value', $organization->external_identifier);
            $organizationGraph->set('schema:identifier', $schemaIdentifier);
            $organizationGraph->set('schema:legalName', $organization->name);
            // add address information
            $organizationAddress = $graph->newBnode('schema:PostalAddress');
            $organizationAddress->set('schema:addressCountry', $organization->ror_country_code);
            $organizationGraph->set('schema:address', $organizationAddress);
            
            $organizationGraph->set('schema:url', Literal::create($organization->ror_website , null, 'xsd:anyURI'));                                    
            
            // Get laboratories belonging to organization
            
            foreach ($organization->laboratories as $laboratory) {
    
                
                if((strlen($laboratory->latitude) == 0) || (strlen($laboratory->longitude) == 0)) {
                    continue;
                }
                
                
                // Add contactpoint of laboratory to graph
                $contactPerson = $laboratory->laboratoryContactPerson;
                
                $contactPointGraph = $graph->resource($this->generateContactPersonURI($contactPerson), 'schema:ContactPoint');
                $contactPointGraph->set('schema:email', $contactPerson->email);
                $contactPointGraph->set('schema:availableLanguage', 'en');
                $contactPointGraph->set('schema:contactType', 'Contact');
                
                // Add Laboratory to graph
                $facilityGraph = $graph->resource($this->generateFacilityURI($laboratory), 'epos:Facility');
                $facilityGraph->set('dct:identifier', $facilityGraph->getUri());
                $facilityGraph->set('dct:title', $laboratory->name);
                $facilityGraph->set('dct:description', $this->formatNewlines($laboratory->description));                
                $facilityGraph->set('dct:type', ['type' => 'uri', 'value' => '<epos:Laboratory>']);
                $facilityGraph->set('dcat:theme', ['type' => 'uri', 'value' => $this->getEPOSlabType($laboratory)]);                
                $facilityGraph->set('dcat:contactPoint', ['type' => 'uri', 'value' => $contactPointGraph->getUri()]);
                if((strlen($laboratory->latitude) > 0) && (strlen($laboratory->longitude) > 0)) {
                    $facilityLocation = $graph->newBNode('dct:Location');
                    $facilityLocation->add("locn:geometry", Literal::create($this->generateGeonometryString($laboratory), null, 'gsp:wktLiteral'));
                    $facilityGraph->set('dct:spatial', $facilityLocation);
                }
                
                // Add keywords
                $facilityGraph->set('dcat:keyword', $laboratory->fast_domain_name);
                $facilityKeywords = $laboratory->laboratoryKeywords->pluck('value')->toArray();
                $facilityKeywords = array_unique($facilityKeywords);
                
                foreach ($facilityKeywords as $facilityKeyword) {                    
                    $facilityGraph->add('dcat:keyword', $facilityKeyword);
                }
                
                // optional
                if(strlen($laboratory->website) > 0) {
                    $facilityGraph->add(
                        'foaf:page', Literal::create($laboratory->website, null, 'xsd:anyURI')
                    );
                }
                
                // Add facility to organization owns
                if ($organizationGraph->hasProperty('schema:owns')) {
                    $organizationGraph->add('schema:owns', $facilityGraph);
                } else {
                    $organizationGraph->set('schema:owns', $facilityGraph);
                }
                
                // Add equipment belonging to lab
                foreach ($laboratory->laboratoryEquipment as $equipment) {
                    $equipmentGraph = $graph->resource($this->generateEquipmentURI($equipment), 'epos:Equipment');
                    $equipmentGraph->set('schema:identifier', $this->generateEquipmentURI($equipment));
                    $equipmentGraph->set('schema:name', $equipment->group_name);
                    $equipmentGraph->set('schema:description', $this->formatNewlines($equipment->description));
                    
                    $equipmentGraph->set('dct:type', ['type' => 'uri', 'value' => $this->getEquipmentType($equipment)]);
                    
                    $equipmentGraph->set('dct:isPartOf', ['type' => 'uri', 'value' => $facilityGraph->getUri()]);
                    $equipmentGraph->add('dcat:keyword', $equipment->category_name);
                    $equipmentGraph->add('dcat:keyword', $equipment->type_name);
                    
                    $equipmentLocation = $graph->newBNode('dct:Location');
                    $equipmentLocation->add("locn:geometry", Literal::create($this->generateGeonometryString($laboratory), null, 'gsp:wktLiteral'));
                    $equipmentGraph->set('dct:spatial', $equipmentLocation);
                    
                }
                                              
            }
            
            
        }
        
                
        
        // concepts
        
        // lab concepts
        
        $facilityTypeConcept = $graph->resource('epos:Facility_Type', 'skos:ConceptScheme');
        $facilityTypeConcept->set('dct:description', 'Facility type');        
        $facilityTypeConcept->set('dct:title', 'Facility type');
                
        $laboratoryConcept = $graph->resource('epos:Laboratory', 'skos:Concept');
        $laboratoryConcept->set('skos:definition', 'Laboratory');
        $laboratoryConcept->set('skos:inScheme', ['type' => 'uri', 'value' => '<epos:Facility_Type>']);
        $laboratoryConcept->set('skos:prefLabel', 'Laboratory');
        
        
        // equipment concepts
        
        $equipmentConcept = $graph->resource('epos:Equipment_Type', 'skos:ConceptScheme');
        $equipmentConcept->set('dct:description', 'Equipment type');
        $equipmentConcept->set('dct:title', 'Equipment type');
                
        $equipmentDefinition = $graph->resource('epos:Atomic_Force_Microscopy_definition', 'skos:Concept');
        $equipmentDefinition->set('skos:definition', 'Atomic Force Microscopy');
        $equipmentDefinition->set('skos:inScheme', ['type' => 'uri', 'value' => '<epos:Equipment_Type>']);
        $equipmentDefinition->set('skos:prefLabel', 'Atomic Force Microscopy');
        
        $equipmentDefinition = $graph->resource('epos:Electron_Microscopy_definition', 'skos:Concept');
        $equipmentDefinition->set('skos:definition', 'Electron Microscopy');
        $equipmentDefinition->set('skos:inScheme', ['type' => 'uri', 'value' => '<epos:Equipment_Type>']);
        $equipmentDefinition->set('skos:prefLabel', 'Electron Microscopy');
        
        $equipmentDefinition = $graph->resource('epos:Electron_Probe_Micro_Analyzer_definition', 'skos:Concept');
        $equipmentDefinition->set('skos:definition', 'Electron Probe Micro Analyzer');
        $equipmentDefinition->set('skos:inScheme', ['type' => 'uri', 'value' => '<epos:Equipment_Type>']);
        $equipmentDefinition->set('skos:prefLabel', 'Electron Probe Micro Analyzer');
        
        $equipmentDefinition = $graph->resource('epos:Mass_Spectrometer_definition', 'skos:Concept');
        $equipmentDefinition->set('skos:definition', 'Mass Spectrometer');
        $equipmentDefinition->set('skos:inScheme', ['type' => 'uri', 'value' => '<epos:Equipment_Type>']);
        $equipmentDefinition->set('skos:prefLabel', 'Mass Spectrometer');
        
        $equipmentDefinition = $graph->resource('epos:X-Ray_Tomography_definition', 'skos:Concept');
        $equipmentDefinition->set('skos:definition', 'X-Ray Tomography');
        $equipmentDefinition->set('skos:inScheme', ['type' => 'uri', 'value' => '<epos:Equipment_Type>']);
        $equipmentDefinition->set('skos:prefLabel', 'X-Ray Tomography');
        
        
                
        return $graph->serialise($type);        
    }
    
    private function generateContactPersonURI(LaboratoryContactPerson $contactPerson) {
        // Return orcid if available otherwise generate custom uri
        if(strlen($contactPerson->orcid) > 5) {
            return 'http://orcid.org/' . $contactPerson->orcid . '/contactperson';
        } else {
            return 'https:/epos-msl/contactperson/' . $contactPerson->fast_id;
        }        
    }
    
    private function generateFacilityURI(Laboratory $laboratory) {
        return 'https:/epos-msl/facility/' . $laboratory->fast_id;
    }
    
    private function generateEquipmentURI(LaboratoryEquipment $equipment) {
        return 'https:/epos-msl/equipment/' . $equipment->fast_id;
    }
    
    private function generateGeonometryString(Laboratory $laboratory) {        
        return "POINT(" . str_replace(',', '.', $laboratory->longitude) . " " . str_replace(',', '.', $laboratory->latitude) . ")";
    }
    
    private function getEPOSlabType(Laboratory $laboratory) {
        $fastLaboratoryDomain = $laboratory->fast_domain_name;
        
        switch ($fastLaboratoryDomain) {
            case 'Analogue modelling of geological processes':
                return '<category:AnalogueModelling_conc>';
            
            case 'Geochemistry':
                return '<category:Geochemistry_conc>';
                
            case 'Microscopy and tomography':
                return '<category:MicroscopyAndTomography_conc>';
                
            case 'Paleomagnetism':
                return '<category:Paleomagnetism_conc>';
                
            case 'Rock and melt physics':
                return '<category:RockPhysics_conc>';

            case 'Geo-energy':
                return '<category:GeoEnergy_conc>';
                
            default:
                return '';
        }
    }
    
    private function getEquipmentType(LaboratoryEquipment $laboratoryEquipment) {
        $equipmentType = $laboratoryEquipment->type_name;
        
        switch ($equipmentType) {
            case 'Atomic Force Microscopy':
                return '<epos:Atomic_Force_Microscopy_definition>';
                
            case 'Electron Microscopy':
                return '<epos:Electron_Microscopy_definition>';
                
            case 'Electron Probe Micro Analyzer':
                return '<epos:Electron_Probe_Micro_Analyzer_definition>';
                
            case 'Mass Spectrometer':
                return '<epos:Mass_Spectrometer_definition>';
                
            case 'X-Ray Tomography':
                return '<epos:X-Ray_Tomography_definition>';
            
            default:
                return '';
        }
    }
    
    private function formatNewlines($text) {
        return preg_replace('/\R/', '', nl2br($text, false));
        return str_replace(PHP_EOL, '', nl2br($text, false));
    }
}

