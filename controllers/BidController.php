<?php
namespace App\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Conditions;
use App\Models\FileToUpload;
use App\Models\Stamp;
use App\Providers\Validator;
use App\Providers\View;

class BidController
{
    public function store($data = [])
    {

        $validator = new Validator;
        $validator->field('bid', $data['bid'], "Le champ enchère")->number();

        if (! empty($_SESSION)) {

            if ($validator->isSuccess()) {

                $objAuction     = new Auction;
                $auctionJustBid = $objAuction->selectId($data["id"]);

                $objBid = new Bid;
                $maxBid = $objBid->selectWhereIdMax("auction_id", $data["id"], "bid");

                if ($data["bid"] > $auctionJustBid["startPrice"] && $data["bid"] > $maxBid["bid"]) {
                    $dataInsert               = [];
                    $dataInsert["bid"]        = $data["bid"];
                    $dataInsert["auction_id"] = $data["id"];
                    $dataInsert["user_id"]    = $_SESSION['user_id'];

                    $insertBid = $objBid->insert($dataInsert);
                    if ($insertBid) {
                        return view::redirect('auction');
                    } else {
                        return View::render('error', ['msg' => "Impossible de saisir l'enchère"]);
                    }

                } else {
                    return View::render('error', ['msg' => "Votre enchère est trop petite."]);
                }

            } else {

                $auctions = $objAuction->select();

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

                $errors = $validator->getErrors();
                return View::render('auction/catalogue', ['errors' => $errors, 'bidData' => $data, 'auctions' => $auctions]);
            }

        } else {
            return view::redirect('login');
        }
    }
}
