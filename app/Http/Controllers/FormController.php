<?php

namespace App\Http\Controllers;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageShowRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormController extends Controller
{
    /**
     * Show the contact us form
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contactForm(): View
    {
        return view('forms.contact-us');
    }
 
    /**
     * Process the contact us form
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function contactFormProcess(Request $request): RedirectResponse
    {

        $formFields = $request->validate([
            'email'         => ['required', 'email'],
            'firstName'     => ['required'],
            'lastName'      => ['required'],
            'affiliation'   => ['required'],
            'subject'       => ['required'],
            'message'       => ['required', 'min:50'],
        ]);


        // redirects to with the additonal elements located in components/notifications/
        return redirect('/')->with('modals', [
            'type'      => 'success', 
            'message'   => 'Contact request sent. You will receive a confirmation email soon, please check your spam as well']
         );
    }

    /**
     * Show the lab signup form
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function labIntakeForm(): View
    {
        return view('forms.laboratory-intake');
    }
 
    /**
     * Process the lab signup form
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function labIntakeFormProcess(Request $request): RedirectResponse
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
            'subdomain'           => ['required'],     
            "contact-firstName"   => ['required'],
            "contact-lastName"    => ['required'],
            "contact-nationality" => ['required'],
            "contact-gender"      => ['required'],
            "contact-email"       => ['required', 'email'],
            "contact-affiliation" => ['required'],
            "contact-affiliation-country" => ['required'],

        ]);

        return redirect('/contribute-laboratory#nextStep')->with('modals', [
            'type'      => 'success', 
            'message'   => 'contact request sent. You will receive a confirmation email soon, please check your spam as well']
         );
    }

    /**
     * Show the lab contact form
     * 
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function labContactForm($id): View
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
     * Process the lab contact form
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function labContactFormProcess(Request $request): RedirectResponse
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

        return redirect('/contribute-laboratory#nextStep')->with('modals', [
            'type'      => 'success', 
            'message'   => 'contact request sent. You will receive a confirmation email soon, please check your spam as well']
         );
    }

}
