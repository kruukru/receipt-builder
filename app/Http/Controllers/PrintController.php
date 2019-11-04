<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Response;

use DummyPrintConnector;
use Printer;
use NetworkPrintConnector;
use CapabilityProfile;
use WindowsPrintConnector;
use EscposImage;
use URL;
use RawbtPrintConnector;

class PrintController extends Controller
{
    public function postPrintReceipt(Request $request) {
        $printString = "<BR><CENTER><BIG>5J Store<BR>1326 Masinop St. Tondo Manila<BR><CENTER>".date("l, M j, Y G:i")."<BR><DLINE0><BR>";
        $printString .= $this->funcPrintOrder($request->arrayorder);
        $printString .= "<LINE0><BR>";
        $printString .= $this->funcPrintSummary(array(
            "Total" => $request->totalprice,
            "Cash Tendered" => $request->cashtendered,
            "Change" => $request->change,
            "Balance" => abs($request->balance),
        ));
        $printString .= "<BR><BR><CUT>";

        return Response::json(array(
        	'print' => $printString
        ));
    }

    public function funcPrintOrder($arrayOrder) {
        $printString = "";

        foreach ($arrayOrder as $key => $order) {
        	$productName = Product::findOrFail($order['id']);

            $outPrice = "";
            if ($order['quantity'] > 1) {
                $outPrice = "(".number_format($order['price'], 2).")";
            }

            $printString .= "<BOLD><LEFT>".$order['quantity']." <NORMAL>- ".$productName->name." ".$outPrice."<BR><BOLD><RIGHT>".number_format($order['totalprice'], 2)."<NORMAL><BR>";
        }

        return $printString;
    }
    public function funcPrintSummary($arrayItem) {
        $printString = "";

        foreach ($arrayItem as $key => $item) {
            $printString .= "<BOLD><LEFT>".$key."<BR><BOLD><RIGHT>".number_format($item, 2)."<BR>";
        }

        return $printString;
    }
}

// - raw abt app - issue on android version 4
// public function postPrintReceipt(Request $request) {
//     $connector = new DummyPrintConnector();
//     $printer = new Printer($connector);
//     // $connector = new WindowsPrintConnector("Thermal Printer");
//     // $printer = new Printer($connector);

//     // $logo = EscposImage::load("images/test1.png", false); //path for this will be the public folder
//     // $printer->bitImage($logo);

//     $printer->feed();
//     $printer->setJustification(Printer::JUSTIFY_CENTER);
//     $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
//     $printer->setTextSize(2, 2);
//     $printer->text("5J Store");
//     $printer->feed();
//     $printer->selectPrintMode();
//     $printer->text("1326 Masinop St. Tondo Manila");
//     $printer->feed();
//     $printer->text(date("l, M j, Y G:i"));
//     $printer->feed(2);
//     $printer = $this->funcPrintItem($request->arrayorder, $printer);
//     $printer->feed();
//     $printer->setEmphasis(true);

//     $printer->setJustification(Printer::JUSTIFY_LEFT);
//     $printer->text("Total");
//     $printer->feed();
//     $printer->setJustification(Printer::JUSTIFY_RIGHT);
//     $printer->text("P ".number_format($request->totalprice, 2));
//     $printer->feed();

//     $printer->setJustification(Printer::JUSTIFY_LEFT);
//     $printer->text("Cash Tendered");
//     $printer->feed();
//     $printer->setJustification(Printer::JUSTIFY_RIGHT);
//     $printer->text("P ".number_format($request->cashtendered, 2));
//     $printer->feed();

//     $printer->setJustification(Printer::JUSTIFY_LEFT);
//     $printer->text("Change");
//     $printer->feed();
//     $printer->setJustification(Printer::JUSTIFY_RIGHT);
//     $printer->text("P ".number_format($request->change, 2));
//     $printer->feed();

//     $printer->setJustification(Printer::JUSTIFY_LEFT);
//     $printer->text("Balance");
//     $printer->feed();
//     $printer->setJustification(Printer::JUSTIFY_RIGHT);
//     $printer->text("P ".number_format(abs($request->balance), 2));
//     $printer->feed();

//     $printer->setEmphasis(false);
//     $printer->feed(4);
//     $printer->cut();
//     $thisData = $connector->getData();
//     $printer->close();

//     return Response::json(array(
//         'print' => $thisData,
//         'print64' => base64_encode($thisData),
//     ));
// }

// public function funcPrintItem($arrayItem, $printer) {
//     $total = 0;

//     foreach ($arrayItem as $key => $item) {
//         $productName = Product::findOrFail($item['id']);

//         $printer->setJustification(Printer::JUSTIFY_LEFT);
//         $printer->text($item['quantity']. " - " .$productName->name." (P ".number_format($item['price'], 2).")");
//         $printer->feed();
//         $printer->setJustification(Printer::JUSTIFY_RIGHT);
//         $printer->setEmphasis(true);
//         $printer->text("P ".number_format($item['totalprice'], 2));
//         $printer->setEmphasis(false);
//         $printer->feed();
//     }

//     return $printer;
// }