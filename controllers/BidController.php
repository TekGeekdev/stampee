<?php
namespace App\Controllers;

use App\Providers\Validator;
use App\Providers\View;
use App\Models\Stamp;
use App\Models\FileToUpload;
use App\Models\Conditions;
use App\Models\Auction;

class BidController
{
    public function store($data = [])
    {

        $validator = new Validator;
        $validator->field('bid', $data['bid'], "Le champ enchère")->number();

        if (! empty($_SESSION)) {
            if ($validator->isSuccess()) {} else {
                $objAuction = new Auction;
                $auctions   = $objAuction->select();

                $objStamp        = new Stamp;
                $objFileToUpload = new FileToUpload;
                $obtCondition    = new Conditions;

                foreach ($auctions as $auctionsIndex => $auction) {
                    $stampInfo                               = $objStamp->selectId($auction['stamp_id']);
                    $auctions[$auctionsIndex]['nameStamp']   = $stampInfo['name'];
                    $auctions[$auctionsIndex]['dateRelease'] = $stampInfo['dateRelease'];

                    $conditionInfo                         = $obtCondition->selectId($stampInfo['conditions_id']);
                    $auctions[$auctionsIndex]['condition'] = $conditionInfo["state"];

                    $stampImages = $objFileToUpload->selectAllById($auction['stamp_id'], "stamp_id");

                    foreach ($stampImages as $stampImagesIndex => $image) {
                        if ($image["position"] == 0) {
                            $auctions[$auctionsIndex]['fileName']        = $image['file'];
                            $auctions[$auctionsIndex]['fileDescription'] = $image['description'];
                        }
                    }
                }
                echo('<pre>');
                print_r($data);
                echo('</pre>');
                // die();
                $errors = $validator->getErrors();
                return View::render('auction/catalogue', ['errors' => $errors, 'bidData' => $data, 'auctions' => $auctions]);
            }

        } else {
            return view::redirect('login');
        }
        // echo('<pre>');
        // print_r($_SESSION);
        // print_r($data);
        // echo('</pre>');
        // die();
    }
}
