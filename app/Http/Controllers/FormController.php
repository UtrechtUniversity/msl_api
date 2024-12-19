<?php

namespace App\Http\Controllers;
use App\CkanClient\Client;

use App\CkanClient\Request\OrganizationListRequest;
use App\CkanClient\Request\PackageSearchRequest;
use App\CkanClient\Request\PackageShowRequest;
use App\Mail\ContactUsResponse;
use App\Mail\LabContactPersonResponse;
use App\Mail\LabContactResponse;
use App\Mail\MarkTestMail;
use App\Models\Keyword;
use App\Models\Laboratory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class FormController extends Controller
{
    public function contactCreate(): View
    {
        return view('forms.contact-us');
    }
 
    /**
     * Store a new blog post.
     */
    public function contactStore(Request $request): RedirectResponse
    {

        $formFields = $request->validate([
            'email'         => ['required', 'email'],
            'firstName'     => ['required'],
            'lastName'      => ['required'],
            'affiliation'   => ['required'],
            'subject'       => ['required'],
            'message'       => ['required', 'min:50'],
        ]);

        // ####################################
        // is the naming 'user' and 'server' appropiate or better have a new email class?
        Mail::to('m.nothbaum@uu.nl')->send(new LabContactPersonResponse($formFields, 'user'));
        Mail::to('m.nothbaum@uu.nl')->send(new LabContactPersonResponse($formFields, 'server'));
        // ####################################


        // redirects to with the additonal elements located in components/notifications/
        return redirect('/')->with('modals', [
            'type'      => 'success', 
            'message'   => 'Contact request sent. You will receive a confirmation email soon, please check your spam as well']
         );
    }

    /////////////////////////////////////////////
    /////////////////////////////////////////////
    /////////////////////////////////////////////

    public function labCreate(): View
    {
        return view('forms.laboratory-intake');
    }
 
    /**
     * Store a new blog post.
     */
    public function labStore(Request $request): RedirectResponse
    {
        //  in order of appearance
        $formFields = $request->validate([
            'lab-name'            => ['required'],
            'street'              => ['required'],
            'street-no'           => ['required'],
            'street-detail'       => ['nullable'],
            'postalCode'          => ['required'],
            'city'                => ['required'],
            'state'               => ['required'],
            'country'             => ['required'],
            'url'                 => ['required', 'url'],
     
            "description"         => ['required','min:10','max:4000'],

            // custom error message in the intake form below the checkboxes
            "dataSharing-facilityAccess" => ['required'],
            // "facilityAccess"      => ['required_without:dataSharing', 'nullable'],
     
            'subdomain'           => ['required'],
     
            "contact-firstName"   => ['required'],
            "contact-lastName"    => ['required'],
            "contact-nationality" => ['required'],
            "contact-gender"      => ['required'],
            "contact-email"       => ['required', 'email'],
            "contact-affiliation" => ['required'],
            "contact-affiliation-country" => ['required'],

        ]);


        Mail::to('m.nothbaum@uu.nl')->send(new LabContactResponse($formFields, 'user'));
        Mail::to('m.nothbaum@uu.nl')->send(new LabContactResponse($formFields, 'server'));


        // I dont like that the highlighted one is always on top of the page right under the edge
        return redirect('/contribute-laboratory#nextStep')->with('modals', [
            'type'      => 'success', 
            'message'   => 'contact request sent. You will receive a confirmation email soon, please check your spam as well']
         );
    }

    /////////////////////////////////////////////
    /////////////////////////////////////////////
    /////////////////////////////////////////////

    public function labContactPersonCreate($id): View
    {

        $client = new Client();
        $request = new PackageShowRequest();
        $request->id = $id;

        $result = $client->get($request);

        if(!$result->isSuccess()) {
            abort(404, 'ckan request failed');
        }
        return view('forms.laboratory-contact-person',['data' => $result->getResult()]);
    }
 
    /**
     * Store a new blog post.
     */
    public function labContactPersonStore(Request $request): RedirectResponse
    {
        //  in order of appearance
        $formFields = $request->validate([
            'email'         => ['required', 'email'],
            'firstName'     => ['required'],
            'lastName'      => ['required'],
            'affiliation'   => ['required'],
            'subject'       => ['required'],
            'message'       => ['required', 'min:50'],
        ]);

        // is the naming appropiate or better have a new email class?
        Mail::to('m.nothbaum@uu.nl')->send(new LabContactPersonResponse($formFields, 'user'));
        Mail::to('m.nothbaum@uu.nl')->send(new LabContactPersonResponse($formFields, 'server'));


        // I dont like that the highlighted one is always on top of the page right under the edge
        return redirect('/contribute-laboratory#nextStep')->with('modals', [
            'type'      => 'success', 
            'message'   => 'contact request sent. You will receive a confirmation email soon, please check your spam as well']
         );
    }

}
