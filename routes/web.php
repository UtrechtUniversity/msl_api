<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Models\User;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\SeederController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\FujiController;

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
Route::get('tools/convert-excel', [ToolsController::class, 'convertExcel'])->name('convert-excel');
Route::post('tools/convert-excel', [ToolsController::class, 'processExcelToJson'])->name('process-excel-file');
Route::get('tools/doi-export', [ToolsController::class, 'doiExport'])->name('doi-export');
Route::get('tools/urilabels', [ToolsController::class, 'uriLabels'])->name('uri-labels');
Route::get('tools/urilabelsdownload', [ToolsController::class, 'uriLabelsDownload'])->name('uri-label-download');

Route::get('tools/filtertree', [ToolsController::class, 'filterTree'])->name('filter-tree');
Route::get('tools/filtertreedownload', [ToolsController::class, 'filterTreeDownload'])->name('filter-tree-download');
Route::get('tools/filtertreedownloadoriginal', [ToolsController::class, 'filterTreeDownloadOriginal'])->name('filter-tree-download-original');
Route::get('tools/unmatchedkeywords', [ToolsController::class, 'viewUnmatchedKeywords'])->name('view-unmatched-keywords');
Route::get('tools/unmatchedkeywordsdownload', [ToolsController::class, 'downloadUnmatchedKeywords'])->name('download-unmatched-keywords');
Route::get('tools/abstract-matching', [ToolsController::class, 'abstractMatching'])->name('abstract-matching');
Route::get('tools/abstract-matching-download/{data_repo}', [ToolsController::class, 'abstractMatchingDownload'])->name('abstract-matching-download');
Route::get('tools/query-generator', [ToolsController::class, 'queryGenerator'])->name('query-generator');

Route::get('tools/fuji/add-dois', [FujiController::class, 'addDois'])->name('fuji-add-dois');
Route::post('tools/fuji/add-dois', [FujiController::class, 'processDois'])->name('fuji-process-dois');
Route::get('tools/fuji/view-groups', [FujiController::class, 'viewAssessmentGroups'])->name('fuji-view-assessment-groups');
Route::get('tools/fuji/view-groups/{group_id}', [FujiController::class, 'viewAssessmentGroup'])->name('fuji-view-assessment-group');
Route::get('tools/fuji/assessment/{assessment_id}', [FujiController::class, 'viewAssessment'])->name('fuji-view-assessment');
Route::get('tools/fuji/download-report', [FujiController::class, 'downloadFujiReport'])->name('fuji-download-report');

Route::get('labs/import-labdata', [LabController::class, 'importLabData'])->name('import-labdata');
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