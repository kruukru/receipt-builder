<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Auth;

use DummyPrintConnector;
use Printer;
use NetworkPrintConnector;
use CapabilityProfile;
use WindowsPrintConnector;
use EscposImage;
use URL;
use RawbtPrintConnector;

class HomeController extends Controller {
    public function index() {
    	if (Auth::check()) {
    		return view("admin.index");
    	} else {
    		$connector = new DummyPrintConnector();
			$printer = new Printer($connector);

            // $connector = new WindowsPrintConnector("Thermal Printer");
            // $printer = new Printer($connector);
            $arrayItem = array(
                array(
                    "count" => 1,
                    "name" => "Nescafe Twin Pack",
                    "price" => 15,
                ),
                array(
                    "count" => 4,
                    "name" => "Ginebra San Miguel",
                    "price" => 20,
                ),
                array(
                    "count" => 10,
                    "name" => "Lucky Me Pancit Canton",
                    "price" => 10,
                ),
            );
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $logo = EscposImage::load("images/test1.png", false); //path for this will be the public folder
            // $printer->bitImage($logo);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->setTextSize(2, 2);
            $printer->text("5J Store");
            $printer->feed();
            $printer->selectPrintMode();
            $printer->text("1326 Masinop St. Tondo Manila");
            $printer->feed();
            $printer->text(date("l, M j, Y G:i"));
            $printer->feed(2);
            $returnArray = $this->funcPrintItem($arrayItem, $printer);
            $printer = $returnArray['printer'];
            $printer->feed();
            $printer->setEmphasis(true);
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("TOTAL");
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("P ".number_format($returnArray['total'], 2));
            $printer->setEmphasis(false);
            $printer->feed(3);
            $printer->cut();
            // $printer->close();

            return view("index.index", [
                "print" => $connector->getData(),
                "print64" => base64_encode($connector->getData()),
                "printer" => $printer,
            ]);
    	}
    }

    public function funcPrintItem($arrayItem, $printer) {
        $total = 0;

        foreach ($arrayItem as $key => $item) {
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($item['count'] . " - " .$item['name']." (P ".number_format($item['price'], 2).")");
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setEmphasis(true);
            $printer->text("P ".number_format($item['price'] * $item['count'], 2));
            $printer->setEmphasis(false);
            $printer->feed();

            $itemTotal = ($item['price'] * $item['count']);
            $total = $total + floatval($itemTotal);
        }

        return array(
            'total' => $total,
            'printer' => $printer,
        );
    }
}
