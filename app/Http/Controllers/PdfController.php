<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PatientInvoiceFiles;

class PdfController extends Controller
{
    public function generateInvoicePdf($data = null)
    {
        // Create a new Dompdf instance
        $dompdf = new Dompdf();

        // Load the HTML content for the PDF

        $html = view('pdf.invoice', ['data' => $data])->render();
        $dompdf->loadHtml($html);

        // Set paper size and orientation (optional)
        $dompdf->setPaper('A4', 'portrait');
        // $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to the browser
        // $dompdf->stream('invoice.pdf');

        // Save the PDF to the storage directory
        $patient_info = '';
        if (isset($data['patient_id'])){
            $patient_info = '_'.$data['patient_id'];
        }
        $filename = 'invoice_'.date('YmdHis').$patient_info.'.pdf';
        if (!is_dir(storage_path('app/public/invoice'))) {
            mkdir(storage_path('app/public/invoice'), 0775, true);
        }
        Storage::put('public/invoice/'.$filename, $dompdf->output());
        if(isset($data['transaction_id']) && $data['transaction_id'] > 0){
            $invoice_rcord = PatientInvoiceFiles::query()->where('transaction_id', $data['transaction_id'])->get()->first();
            if ($invoice_rcord){
                PatientInvoiceFiles::query()->where('transaction_id', $data['transaction_id'])->update(['invoice_file' => $filename, 'updated_at' => date('Y-m-d H:i:s')]);
            } else {
                $new_record = new PatientInvoiceFiles();
                $new_record->transaction_id = $data['transaction_id'] ;
                $new_record->invoice_file = $filename;
                $new_record->save();
            }
        }
    }
}
