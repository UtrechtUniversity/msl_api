<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SeederController;
use App\Http\Controllers\ToolsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Auth::routes(['register' => false]);

Route::prefix('admin')->group(function () {
    Auth::routes(['register' => false]);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/remove-dataset', [HomeController::class, 'removeDataset'])->name('remove-dataset');
    Route::get('/remove-dataset/confirm', [HomeController::class, 'removeDatasetConfirm'])->name('remove-dataset-confirm');
    Route::post('/remove-dataset/confirmed', [HomeController::class, 'removeDatasetConfirmed'])->name('remove-dataset-confirmed');
    Route::get('/queues', [HomeController::class, 'queues'])->name('queues');
    Route::get('/delete-actions', [HomeController::class, 'deleteActions'])->name('delete-actions');

    Route::get('/importers', [HomeController::class, 'importers'])->name('importers');
    Route::get('/importer/{id}/imports', [HomeController::class, 'importerImports'])->name('importer-imports');
    Route::get('/importer/{importer_id}/imports/{import_id}/flow', [HomeController::class, 'importerImportsFlow'])->name('importer-imports-flow');
    Route::get('/importer/{importer_id}/imports/{import_id}/log', [HomeController::class, 'importerImportsLog'])->name('importer-imports-log');
    Route::get('/importer/{importer_id}/imports/{import_id}/log-export', [HomeController::class, 'exportImportLog'])->name('importer-imports-log-export');
    Route::get('/importer/{importer_id}/imports/{import_id}/detail/{source_dataset_identifier_id}', [HomeController::class, 'importerImportsDetail'])->name('importer-imports-detail');

    Route::get('/seeders', [SeederController::class, 'index'])->name('seeders');
    Route::post('/create-seed', [SeederController::class, 'createSeed'])->name('create-seed');
    Route::get('/seeder/{id}/seeds', [SeederController::class, 'seederSeeds'])->name('seeder-seeds');
    Route::get('/seeds/{id}', [SeederController::class, 'seeds'])->name('seeds');

    Route::get('tools/convert-keywords', [ToolsController::class, 'convertKeywords'])->name('convert-keywords');
    Route::post('tools/convert-keywords', [ToolsController::class, 'processMaterialsFile'])->name('process-materials-file');
    Route::post('tools/convert-porefluids', [ToolsController::class, 'processPorefluidsFile'])->name('process-porefluids-file');
    Route::post('tools/convert-rockphysics', [ToolsController::class, 'processRockPhysicsFile'])->name('process-rockphysics-file');
    Route::post('tools/convert-analogue', [ToolsController::class, 'processAnalogueModellingFile'])->name('process-analogue-file');
    Route::post('tools/convert-geological-age', [ToolsController::class, 'processGeologicalAgeFile'])->name('process-geological-age-file');
    Route::post('tools/convert-geological-setting', [ToolsController::class, 'processGeologicalSettingFile'])->name('process-geological-setting-file');
    Route::post('tools/convert-paleomagnetism', [ToolsController::class, 'processPaleomagnetismFile'])->name('process-paleomagnetism-file');
    Route::post('tools/convert-geochemistry', [ToolsController::class, 'processGeochemistryFile'])->name('process-geochemistry-file');
    Route::post('tools/convert-microscopy', [ToolsController::class, 'processMiscroscopyFile'])->name('process-microscopy-file');
    Route::post('tools/convert-subsurface', [ToolsController::class, 'processSubsurfaceFile'])->name('process-subsurface-file');
    Route::post('tools/convert-testbeds', [ToolsController::class, 'processTestbedsFile'])->name('process-testbeds-file');
    Route::get('tools/convert-excel', [ToolsController::class, 'convertExcel'])->name('convert-excel');
    Route::post('tools/convert-excel', [ToolsController::class, 'processExcelToJson'])->name('process-excel-file');
    Route::get('tools/doi-export', [ToolsController::class, 'doiExport'])->name('doi-export');

    Route::get('tools/geoview', [ToolsController::class, 'geoView'])->name('geoview');
    Route::get('tools/geoview-labs', [ToolsController::class, 'geoViewLabs'])->name('geoview-labs');

    Route::get('tools/urilabels', [ToolsController::class, 'uriLabels'])->name('uri-labels');
    Route::get('tools/urilabelsdownload', [ToolsController::class, 'uriLabelsDownload'])->name('uri-label-download');

    Route::get('tools/filtertree', [ToolsController::class, 'filterTree'])->name('filter-tree');
    Route::get('tools/filtertreedownload', [ToolsController::class, 'filterTreeDownload'])->name('filter-tree-download');
    Route::get('tools/filtertreedownloadoriginal', [ToolsController::class, 'filterTreeDownloadOriginal'])->name('filter-tree-download-original');
    Route::get('tools/filtertreedownloadEquipment', [ToolsController::class, 'filterTreeDownloadEquipment'])->name('filter-tree-download-equipment');
    Route::get('tools/unmatchedkeywords', [ToolsController::class, 'viewUnmatchedKeywords'])->name('view-unmatched-keywords');
    Route::get('tools/unmatchedkeywordsdownload', [ToolsController::class, 'downloadUnmatchedKeywords'])->name('download-unmatched-keywords');
    Route::get('tools/abstract-matching', [ToolsController::class, 'abstractMatching'])->name('abstract-matching');
    Route::get('tools/abstract-matching-download/{data_repo}', [ToolsController::class, 'abstractMatchingDownload'])->name('abstract-matching-download');
    Route::get('tools/query-generator', [ToolsController::class, 'queryGenerator'])->name('query-generator');

    Route::get('labs/import-labdata', [LabController::class, 'importLabData'])->name('import-labdata');
    Route::get('labs/laboratories', [LabController::class, 'viewLabData'])->name('view-labdata');
    Route::post('labs/update-fast-data', [LabController::class, 'updateFastData'])->name('update-fast-data');
    Route::get('labs/update-organizations-data', [LabController::class, 'updateLaboratoryOrganizationsByROR'])->name('update-lab-organizations-data');
    Route::get('labs/update-laboratory-keywords', [LabController::class, 'updateLaboratoryKeywords'])->name('update-laboratory-keywords');
    Route::get('labs/registry-turtle', [LabController::class, 'registryTurtle'])->name('registry-turtle');

    Route::post('/create-import', [HomeController::class, 'createImport'])->name('create-import');
    Route::get('/imports', [HomeController::class, 'imports'])->name('imports');
    Route::get('/source-dataset-identifiers', [HomeController::class, 'sourceDatasetIdentifiers'])->name('source-dataset-identifiers');
    Route::get('/source-datasets', [HomeController::class, 'sourceDatasets'])->name('source-datasets');
    Route::get('/source-datasets/{id}', [HomeController::class, 'sourceDataset'])->name('source-dataset');
    Route::get('/create-actions', [HomeController::class, 'createActions'])->name('create-actions');
    Route::get('/create-action/{id}', [HomeController::class, 'createAction'])->name('create-action');

    Route::get('/test', [HomeController::class, 'test'])->name('test');
});

Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/data-access', [FrontendController::class, 'dataPublications'])->name('data-access');
Route::get('/labs/map', [FrontendController::class, 'labsMap'])->name('labs-map');
Route::get('/labs/list', [FrontendController::class, 'labsList'])->name('labs-list');
Route::get('/lab/{id}', [FrontendController::class, 'lab'])->name('lab-detail');
Route::get('/lab/{id}/equipment', [FrontendController::class, 'labEquipment'])->name('lab-detail-equipment');
Route::get('/equipment/map', [FrontendController::class, 'equipmentMap'])->name('equipment-map');
Route::get('/equipment/list', [FrontendController::class, 'equipmentList'])->name('equipment-list');
Route::get('/data-repositories', [FrontendController::class, 'dataRepositories'])->name('data-repositories');
Route::get('/contribute-researcher', [FrontendController::class, 'contributeResearcher'])->name('contribute-researcher');
Route::get('/contribute-repository', [FrontendController::class, 'contributeRepository'])->name('contribute-repository');
Route::get('/contribute-laboratory', [FrontendController::class, 'contributeLaboratory'])->name('contribute-laboratory');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/data-publication/{id}', [FrontendController::class, 'dataPublication'])->name('data-publication-detail');
Route::get('/data-publication/{id}/files', [FrontendController::class, 'dataPublicationFiles'])->name('data-publication-detail-files');
Route::get('/keyword-selector', [FrontendController::class, 'keywordSelector'])->name('keyword-selector');
Route::post('/keyword-export', [FrontendController::class, 'keywordExport'])->name('keyword-export');
Route::get('/themeTest', [FrontendController::class, 'themeTest'])->name('themeTest');

Route::get('/contact-us', [FormController::class, 'contactCreate'])->name('contact-us');
Route::post('/contact-us', [FormController::class, 'contactStore'])->name('contact-us-process');
Route::get('/laboratory-intake', [FormController::class, 'labCreate'])->name('laboratory-intake');
Route::post('/laboratory-intake', [FormController::class, 'labStore'])->name('laboratory-intake-process');
Route::get('/laboratory-contact-person/{id}', [FormController::class, 'labContactPersonCreate'])->name('laboratory-contact-person');
Route::post('/laboratory-contact-person', [FormController::class, 'labContactPersonStore'])->name('laboratory-contact-person-process');
