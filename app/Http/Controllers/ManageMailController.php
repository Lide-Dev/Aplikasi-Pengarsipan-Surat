<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class ManageMailController extends UtilityController
{
    protected $defaultColumn = ['id', 'tanggal_surat', 'asal_surat', 'no_surat'];

    public function __construct()
    {
        $this->useTable = true;
        $this->defaultProps = ['dyComponent' => ['name' => config('contentenum.managemailinbox.name')]];
    }

    public function indexInbox(Request $request)
    {
        $this->defaultColumn = ['id', 'tanggal_surat', 'asal_surat', 'no_surat'];
        $this->setDynamicComponent(config('contentenum.managemailinbox.name'), config('contentenum.managemailinbox.selected'));
        $this->setDynamicProps(['page' => 'manageinbox']);
        $this->getMail($request);

        return $this->render('Home/Index');
    }
    public function indexSent(Request $request)
    {
        $this->defaultColumn = ['id', 'tanggal_surat', 'tujuan', 'no_surat'];
        $this->setDynamicComponent(config('contentenum.managemailsent.name'), config('contentenum.managemailsent.selected'));
        $this->setDynamicProps(['page' => 'managesent']);
        $this->getMail($request, false);

        return $this->render('Home/Index');
    }

    public function getMail(Request $request, $isInbox = true)
    {
        if ($isInbox)
            $mail = SuratMasuk::select($this->defaultColumn);
        else
            $mail = SuratKeluar::select($this->defaultColumn);
        // dd( $mail->configTable($request)->paginate(19)->items() );
        $mail = $mail->configTable($request)->paginate(intval($request->query('itemPerPage', 15)));
        $this->setTableConfig($request, $mail);
       // dd($mail->items());
        $this->setTableData($mail->items());
    }
}
