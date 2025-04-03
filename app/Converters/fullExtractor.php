<?php

function definitionForRoot($node, $columnToCheck, $worksheet)
{
    // check rootnode itself
    $getCell = $worksheet->getCell($columnToCheck.$node['rowNr']);
    $cellValue = $worksheet->getCell($columnToCheck.$node['rowNr'])->getValue();

    if ($cellValue != '') {
        $node = checkupValue($cellValue, $getCell, $node);
    }

    return $node;
}

function checkRootNode($node, $columnToCheck, $worksheet)
{
    $newSubNode = [];
    foreach ($node['subTerms'] as $subnode) {
        // recursive
        $subnode = addCellValueToEntry($subnode, $columnToCheck, $worksheet);
        $newSubNode[] = $subnode;
    }

    return $newSubNode;
}

// recursive
function addCellValueToEntry($node, $columnToCheck, $worksheet)
{
    $getCell = $worksheet->getCell($columnToCheck.$node['rowNr']);
    $cellValue = $worksheet->getCell($columnToCheck.$node['rowNr'])->getValue();

    if ($cellValue != '') {
        $node = checkupValue($cellValue, $getCell, $node);
    }

    if (count($node['subTerms']) > 0) {
        $newData = [];

        foreach ($node['subTerms'] as $subNode) {
            $subNode = addCellValueToEntry($subNode, $columnToCheck, $worksheet);
            $newData[] = $subNode;
        }
        $node['subTerms'] = $newData;
    }

    return $node;
}

function checkupValue($cellValue, $getCell, $node)
{
    if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
        $fullstring = '';
        foreach ($cellValue->getRichTextElements() as $richTextElement) {
            $fullstring = $fullstring.$richTextElement->getText();
        }
        $node['defininition'] = $fullstring;
    } else {

        if ($getCell->hasHyperlink()) {
            $node['defininition-link'] = $getCell->getHyperlink()->getUrl();

        } elseif (str_contains(substr($cellValue, 0, 4), 'http')) {

            $node['defininition-link'] = $cellValue;

        } else {
            $node['defininition'] = $cellValue;

        }
    }

    return $node;
}
