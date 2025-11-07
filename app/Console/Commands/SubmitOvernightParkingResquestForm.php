<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use HeadlessChromium\BrowserFactory;

class SubmitOvernightParkingResquestForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'browser:submit-overnight-parking-resquest-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically submit a form for an overnight parking in Cote-Saint Luc.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $browserFactory = new BrowserFactory();

        // Starts Chrome
        $browser = $browserFactory->createBrowser([
            'headless' => true,
        ]);


        try {
            // Creates a new page and navigate to an URL
            $page = $browser->createPage();
            $page->navigate('http://parking.cotesaintluc.org/?lng=en')->waitForNavigation();

            $this->info('Searching for address...');
            $page->evaluate("document.querySelector('#CivicNumberTextBox').value = '5120'");
            $page->evaluate("document.querySelector('#SearchButton').click()");

            $page->waitUntilContainsElement('#AddressesResultsPanel');

            $this->info('Selecting street...');
            $page->evaluate("
                var select = document.querySelector('#StreetDropDownList');
                select.value = '2541';
                select.dispatchEvent(new Event('change', { bubbles: true }));
            ");

            $this->info('Filling personal information...');
            $page->evaluate("document.querySelector('#SuiteTextBox').value = '208'");
            $page->evaluate("document.querySelector('#FirstNameTextBox').value = 'Gideon'");
            $page->evaluate("document.querySelector('#LastNameTextBox').value = 'Andrada'");
            $page->evaluate("document.querySelector('#PhoneTextBox').value = '4383997563'");
            $page->evaluate("document.querySelector('#LicencePlateTextBox').value = 'Z53PTC'");

            $this->info('Selecting vehicle make...');
            $page->evaluate("
                var select = document.querySelector('#VehicleMakeDropDownList');
                select.value = '21';
                select.dispatchEvent(new Event('change', { bubbles: true }));
            ");

            $this->info('Waiting for vehicle model options...');
            // Trigger postback
            $page->evaluate("__doPostBack('VehicleMakeDropDownList', '')");

            // Wait for Vehicle Model dropdown to have options
            $ready = $page->evaluate("
                (async () => {
                    await new Promise((resolve) => {
                        const check = setInterval(() => {
                            const modelDD = document.querySelector('#VehicleModelDropDownList');
                            if (modelDD && modelDD.options.length > 1) {
                                clearInterval(check);
                                resolve(true);
                            }
                        }, 100);
                        setTimeout(() => {
                            clearInterval(check);
                            resolve(false);
                        }, 10000);
                    });
                    return true;
                })()
            ");

            if ($ready->getReturnValue()) {
                $this->info('Vehicle model dropdown ready!');
                // Proceed with next step
                $page->evaluate("
                    var select = document.querySelector('#VehicleModelDropDownList');
                    select.value = '322';
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                ");
            }

            $this->info('Selecting vehicle model...');
            $page->evaluate("
                var select = document.querySelector('#ColorDropDownList');
                select.value = '5';
                select.dispatchEvent(new Event('change', { bubbles: true }));
            ");

            $this->info('Selecting color and reason...');
            $page->evaluate("
                var select = document.querySelector('#ReasonDropDownList');
                select.value = '1';
                select.dispatchEvent(new Event('change', { bubbles: true }));
            ");

            $this->info('Filling additional details...');
            $page->evaluate("document.querySelector('#NumberOfDaysTextBox').value = '3'");
            $page->evaluate("document.querySelector('#EmailTextBox').value = 'andrada.gideon@gmail.com'");

            $this->info('Submitting form...');
            $page->evaluate("document.querySelector('#SubmitButton').click()");

            $page->waitUntilContainsElement('#MessagePanel');

            $ready = $page->evaluate("
                (() => {
                    const message = document.querySelector('#MessagePanel').textContent;
                    return message;
                })();
            ");

            $message = trim($ready->getReturnValue());

            $screenshot = $page->screenshot([
                'captureBeyondViewport' => true,
                'clip' => $page->getFullPageClip()
            ]);

            $screenshotPath = storage_path()."/screenshots/parking_" . date('Y-m-d_His') . ".png";
            $screenshot->saveToFile($screenshotPath);

            $this->info("Form submitted successfully!");
            $this->info("Message: $message");
            $this->info("Screenshot saved: $screenshotPath");

        } finally {
            $browser->close();
        }
    }
}
