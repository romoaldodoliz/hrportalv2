<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\UploadPdf;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;

class UploadPdfNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:upload_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resignation Letter Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notification = $this->notifyGroupOnceEmployeeResigned();
        $this->info($notification);
    }

    public function notifyGroupOnceEmployeeResigned(){

        $upload_pdfs = UploadPdf::with('employee.departments','employee.locations','employee.companies')
                                    ->whereDate('created_at','>=',date('Y-m-d'))
                                    ->where(function($q){
                                        $q->where('notification_webex','!=','1');
                                        $q->orWhere('notification_webex',null);
                                    })
                                    ->where(function($q){
                                        $q->where('cancel','!=','1');
                                        $q->orWhere('cancel',null);
                                    })
                                    ->get();
        
        if(count($upload_pdfs) > 0){

            foreach($upload_pdfs as $k => $item){
                $message = "<span>Resignation Letter has been uploaded. Please check details below. Thank you. </span>
                            <ul>
                                <li>Employee: ".$item['employee']['first_name'] . ' ' . $item['employee']['last_name']."</li>
                                <li>Position: ".$item['employee']['position']."</li>
                                <li>Company: ".$item['employee']['companies'][0]['name']."</li>
                                <li>Department: ".$item['employee']['departments'][0]['name']."</li>
                                <li>Location: ".$item['employee']['locations'][0]['name']."</li>
                                <li>Type: ".$item['type']."</li>
                                <li>Date: ".$item['created_at']."</li>
                            </ul>
                            <p>Link : http://10.96.4.126:8668/uploaded-pdf</p>
                            <hr>";
                $send_webex = $this->sendGroupWebexMessage($message);
                UploadPdf::where('id',$item['id'])->update(['notification_webex'=>'1']);
            }
            return 'sent';
        }

    }

    public function sendGroupWebexMessage($message){

        $httpClient = new Client(); 

        if($message){
            $body = [
                //prod
                'roomId' => 'Y2lzY29zcGFyazovL3VzL1JPT00vMDVlNjYzODAtZGM5Ny0xMWVjLWFmNDMtYzFhMzRjODhkYjBh',
                'html' => $message
            ];

            try{
                $response = $httpClient->post(
                    'https://api.ciscospark.com/v1/messages',
                    [
                        RequestOptions::BODY => json_encode($body),
                        RequestOptions::HEADERS => [
                            'Content-Type' => 'application/json',
                            //prod
                            'Authorization' => 'Bearer NmI3NTIzNzctNzI1NC00M2I1LThmMDctNzliNzg2ZGM2NzMyYjdkZGE0Y2UtOTdh_PF84_72c16376-f5a4-4a5c-ad51-a60a7b78a790',
                        ],
                    ]
                );

            return 'sent';

            }catch(ServerException $e){
                return 'not';
            }
            catch(RequestException $e){
                return 'not';
            }
            catch(ConnectException $e){
                return 'not';
            }
            catch(ClientException $e){
                return 'not';
            }

        }
    }
    
}
