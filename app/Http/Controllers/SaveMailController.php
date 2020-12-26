<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\Tembusan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SaveMailController extends UtilityController
{
    private $rules = [
        'inbox' => [
            'asal_surat' => 'required|string',
            'id_sifat' => 'required|exists:sifatsurat,id|integer',
            'tanggal_surat' => 'required|date_format:d/m/Y|before_or_equal:today',
            'no_surat' => 'required',
            'perihal' => 'required|string|max:255',
            'isi_ringkas' => 'nullable|max:500',
            'tembusan' => 'nullable|max:10',
        ],
        'sent' => [
            'id_sifat' => 'required|exists:sifatsurat,id|integer',
            'tanggal_surat' => 'required|date',
            'no_surat' => 'required',
            'asal_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'isi_ringkas' => 'nullable|max:500'
        ]
    ];

    public function __construct()
    {
        $this->defaultProps = ['dyComponent' => ['name' => config('contentenum.savemailinbox.name')]];
    }

    public function inboxIndex(Request $request)
    {
        $this->setDynamicComponent('', config('contentenum.savemailinbox.selected'), true);
        $this->setDynamicProps(['page' => 'saveinbox']);
        return $this->render('Home/Index');
    }

    public function sentIndex(Request $request)
    {
        $this->setDynamicComponent('', config('contentenum.savemailsent.selected'), true);
        return $this->render('Home/Index');
    }

    public function createMail(Request $request)
    {
        $rules = [];
        $mode = "";
        $models = "";
        if ($request->route()->named('mails.sent.create.post')) {
            $rules = $this->rules['sent'];
            $models = SuratKeluar::class;
            $mode = "sent";
        }
        if ($request->route()->named('mails.inbox.create.post')) {
            $rules = $this->rules['inbox'];
            $models = SuratMasuk::class;
            $mode = "inbox";
        }
        $value_mail = $request->validateWithBag("create.$mode.mail.error", $rules);
        if (!empty($request->input("tembusan"))) {
            $value_tembusan = $request->validateWithBag("create.$mode.tembusan.error", ["tembusan.*" => "required|max:255"]);
            foreach ($value_tembusan as $tembusan) {
                $keys = array_keys($tembusan);
                if (in_array("new", $keys)) {
                    $this->createTembusan($tembusan);
                }
            }
        }
        if (!empty($request->file("files"))) {
            $value_file = $request->validateWithBag("create.$mode.document.error", ["files.*" => "mimes:png,jpg,jpeg,gif,tiff,bmp,zip,rar,tar,7z,pdf,doc,docx,rtf,odt,tex,txt,wpd|max:1024"]);
        }

        dd("SENDED", $request->input(), $request->file(), $models);
        //

        $validated = $request->validate($rules);

        $models::create($validated);
    }


    public function createTembusan($tembusan)
    {

        $new = new Tembusan;
        $new->nama = $tembusan['name'];
        $tembusan->save();
        dd("COMPLETED");

        return ['status' => true];
    }

    public function getTembusan(Request $request)
    {
        $search = $request->input('search', '');
        // dd($search);
        $tembusan = Tembusan::where('nama', 'like', "%$search%")->orderBy('nama', 'asc');
        if (!empty($request->input('restrict', ""))) {
            $tembusan->whereNotIn('id', explode(',', $request->input('restrict', "")));
        }
        $tembusan = $tembusan->limit(7)->get();
        if (count(collect($tembusan)) === 0) {
            return response([], 200);
        } else {
            return response($this->camelCaseConverter($tembusan), 200);
        }
        // return $this->render('Home/Index');
    }
}
