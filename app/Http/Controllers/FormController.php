<?php

namespace App\Http\Controllers;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageShowRequest;
use App\Mail\ContactUsConfirmation;
use App\Mail\ContactUsSubmission;
use App\Mail\LabIntakeConfirmation;
use App\Mail\LabIntakeSubmission;
use App\Models\Laboratory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

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
        // validate input
        $formFields = $request->validate([
            'email'         => ['required', 'email'],
            'firstName'     => ['required'],
            'lastName'      => ['required'],
            'affiliation'   => ['required'],
            'subject'       => ['required'],
            'message'       => ['required', 'min:50']
        ]);

        // send e-mail to notification address containing form submission
        Mail::to(config('mail.notifications.address'))->send(new ContactUsSubmission($formFields));

        // send e-mail to form submitter to confirm form submission
        Mail::to($formFields['email'])->send(new ContactUsConfirmation($formFields));

        // redirects to index with the additonal elements located in components/notifications
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
            "contact-affiliation-country" => ['required']
        ]);

        // send e-mail to notification address containing form submission
        Mail::to(config('mail.notifications.address'))->send(new LabIntakeSubmission($formFields));

        // send e-mail to form submitter to confirm form submission
        Mail::to($formFields['contact-email'])->send(new LabIntakeConfirmation($formFields));

        // redirects to contribute-laboratory with the additonal elements located in components/notifications
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

        /**
         * All labs should have a contact person defined with an email address however this is 
         * depending on harvested data from FAST so we should check if this is the case. Abort 
         * when this page has somehow been reached without valid data to process the form.
         */

         $labData = $result->getResult();
         $labDatabase = Laboratory::where('fast_id', (int)$labData['msl_fast_id'])->first();
 
         if($labDatabase) {
             $contactPerson = $labDatabase->laboratoryContactPerson;
             if(!$contactPerson->hasValidEmail()) {
                 abort(404, 'Invalid lab contact form requested');
             }
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
            'message'       => ['required', 'min:50']
        ]);

        // redirects to contribute-laboratory with the additonal elements located in components/notifications
        return redirect('/contribute-laboratory#nextStep')->with('modals', [
            'type'      => 'success', 
            'message'   => 'contact request sent. You will receive a confirmation email soon, please check your spam as well']
        );
    }

}
