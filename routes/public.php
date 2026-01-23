<?php

use App\Http\Controllers\DataPublicationAccessController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\SeederController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ToolsController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

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
Route::get('/contribute-project', [FrontendController::class, 'contributeProject'])->name('contribute-project');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/data-publication/{id}', [FrontendController::class, 'dataPublication'])->name('data-publication-detail');
Route::get('/data-publication/{id}/files', [FrontendController::class, 'dataPublicationFiles'])->name('data-publication-detail-files');
Route::get('/keyword-selector', [FrontendController::class, 'keywordSelector'])->name('keyword-selector');
Route::post('/keyword-export', [FrontendController::class, 'keywordExport'])->name('keyword-export');

if (App::environment('local')) {
    Route::get('/themeTest', [FrontendController::class, 'themeTest'])->name('themeTest');
    Route::get('/demoPage', [FrontendController::class, 'demoPage'])->name('demoPage');
}

Route::get('/contribute-select-scenario', [FrontendController::class, 'contributeSelectScenario'])->name('contribute-select-scenario');

Route::get('/contact-us', [FormController::class, 'contactForm'])->name('contact-us');
Route::post('/contact-us', [FormController::class, 'contactFormProcess'])->name('contact-us-process')->middleware(ProtectAgainstSpam::class);
Route::get('/laboratory-intake', [FormController::class, 'labIntakeForm'])->name('laboratory-intake');
Route::post('/laboratory-intake', [FormController::class, 'labIntakeFormProcess'])->name('laboratory-intake-process')->middleware(ProtectAgainstSpam::class);
Route::get('/laboratory-contact-person/{id}', [FormController::class, 'labContactForm'])->name('laboratory-contact-person');
Route::post('/laboratory-contact-person', [FormController::class, 'labContactFormProcess'])->name('laboratory-contact-person-process')->middleware(ProtectAgainstSpam::class);

Route::get('/survey-form/{surveyName}', [SurveyController::class, 'surveyForm'])->name('survey-form');
Route::post('/survey-form/{surveyName}', [SurveyController::class, 'surveyProcess'])->name('survey-form-process');

Route::get('/data-publication-map-test', [DataPublicationAccessController::class, 'index'])->name('data-publication-map-test');
