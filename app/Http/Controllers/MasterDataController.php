<?php

namespace App\Http\Controllers;

use App\Models\ComplaintType;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Mitra;
use App\Models\Periode;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Exports\PartnersExport;

class MasterDataController extends Controller
{
    public function facultyIndex()
    {
        $faculties = Faculty::all();

        return view('admin.masterData.facultyIndex')->with('faculties', $faculties);
    }

    public function storeFaculty(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255']
        ]);

        $facultyName = $validated['name'];


        // Cek apakah fakultas sudah ada
        $facultyExist = Faculty::where('name', $facultyName)->first();

        if ($facultyExist) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => ['Fakultas sudah tercatat']
                ],
                400
            );
        }

        // Add new data
        Faculty::create(['name' => $facultyName]);

        return response()->json([
            'success' => true,
            'msg' => 'Data fakultas berhasil ditambah'
        ], 201);
    }

    public function updateFaculty(Request $request)
    {
        $validated = $request->validate([
            'facultyId' => ['required', 'numeric', 'exists:faculties,id'],
            'name' => ['required', 'string', 'min:1', 'max:255']
        ]);

        $id = $validated['facultyId'];
        $facultyName = $validated['name'];

        $facultyExist = Faculty::findOrFail($id);

        // Add new data
        $facultyExist->name = $facultyName;
        $facultyExist->save();

        return response()->json([
            'success' => true,
            'msg' => 'Data fakultas berhasil diedit'
        ], 201);
    }

    public function deleteFaculty(Request $request)
    {
        $validated = $request->validate([
            'facultyId' => ['required', 'numeric', 'exists:faculties,id'],
        ]);

        $id = $validated['facultyId'];

        Faculty::destroy($id);

        return response()->json([
            'success' => true,
            'msg' => 'Data fakultas berhasil dihapus'
        ], 201);
    }

    public function studyProgramIndex()
    {
        $studyPrograms = StudyProgram::all();
        $faculties = Faculty::all();

        return view('admin.masterData.studyProgramIndex')
            ->with('studyPrograms', $studyPrograms)
            ->with('faculties', $faculties);
    }

    public function storeStudyProgram(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'facultyId' => ['required', 'numeric', 'exists:faculties,id'],
        ]);

        $studyProgramName = $validated['name'];
        $facultyId = $validated['facultyId'];


        // Cek apakah fakultas sudah ada
        $studyProgramExist = StudyProgram::where('name', $studyProgramName)->first();

        if ($studyProgramExist) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => ['Program studi sudah tercatat']
                ],
                400
            );
        }

        // Add new data
        StudyProgram::create(['name' => $studyProgramName, 'faculty_id' => $facultyId]);

        return response()->json([
            'success' => true,
            'msg' => 'Data program studi berhasil ditambah'
        ], 201);
    }

    public function updateStudyProgram(Request $request)
    {
        $validated = $request->validate([
            'studyProgramId' => ['required', 'numeric', 'exists:study_programs,id'],
            'facultyId' => ['required', 'numeric', 'exists:faculties,id'],
            'name' => ['required', 'string', 'min:1', 'max:255']
        ]);

        $studyProgramId = $validated['studyProgramId'];
        $studyProgramName = $validated['name'];
        $facultyId = $validated['facultyId'];

        $studyProgramExist = StudyProgram::findOrFail($studyProgramId);

        // Add new data
        $studyProgramExist->name = $studyProgramName;
        $studyProgramExist->faculty_id = $facultyId;
        $studyProgramExist->save();

        return response()->json([
            'success' => true,
            'msg' => 'Data program studi berhasil diedit'
        ], 201);
    }

    public function deleteStudyProgram(Request $request)
    {
        $validated = $request->validate([
            'studyProgramId' => ['required', 'numeric', 'exists:study_programs,id'],
        ]);

        $id = $validated['studyProgramId'];

        StudyProgram::destroy($id);

        return response()->json([
            'success' => true,
            'msg' => 'Data program studi berhasil dihapus'
        ], 201);
    }

    public function complaintTypeIndex()
    {
        $complaintTypes = ComplaintType::all();

        return view('admin.masterData.complaintTypeIndex')
            ->with('complaintTypes', $complaintTypes);
    }

    public function storeComplaintType(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:1', 'max:255']
        ]);

        $title = $validated['title'];

        // Cek apakah fakultas sudah ada
        $complaintExist = ComplaintType::where('title', $title)->first();

        if ($complaintExist) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => ['Jenis pengaduan sudah tercatat']
                ],
                400
            );
        }

        // Add new data
        ComplaintType::create(['title' => $title]);

        return response()->json([
            'success' => true,
            'msg' => 'Jenis pengaduan berhasil ditambah'
        ], 201);
    }

    public function updateComplaintType(Request $request)
    {
        $validated = $request->validate([
            'complaintTypeId' => ['required', 'numeric', 'exists:complaint_types,id'],
            'title' => ['required', 'string', 'min:1', 'max:255']
        ]);

        $id = $validated['complaintTypeId'];
        $title = $validated['title'];

        $complaintExist = ComplaintType::findOrFail($id);

        // Add new data
        $complaintExist->title = $title;
        $complaintExist->save();

        return response()->json([
            'success' => true,
            'msg' => 'Jenis pengaduan berhasil diedit'
        ], 201);
    }

    public function deleteComplaintType(Request $request)
    {
        $validated = $request->validate([
            'complaintTypeId' => ['required', 'numeric', 'exists:complaint_types,id'],
        ]);

        $id = $validated['complaintTypeId'];

        ComplaintType::destroy($id);

        return response()->json([
            'success' => true,
            'msg' => 'Jenis pengaduan berhasil dihapus'
        ], 201);
    }

    // Partners
    public function partnerIndex()
    {
        $mitras = Mitra::all();
        $totalPartners = Mitra::count();
        $totalActivePartners = Mitra::where('status', '=', '1' )->count();
        $totalNonActivePartners = Mitra::where('status', '=', '0' )->count();
        return view('admin.masterData.partner.partnerIndex')->with([
            'mitras' => $mitras,
            'totalPartners' => $totalPartners,
            'totalActivePartners' => $totalActivePartners,
            'totalNonActivePartners' => $totalNonActivePartners,
        ]);
    }

    # Create Partner

    public function create()
    {
        return view('admin.masterData.partner.create');
    }

    public function storePartner(Request $request)
    {
        $validatedData = $request->validate([
            'partner_name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email', 'min:1', 'max:255', 'unique:mitras,email'],
            'description' => ['required', 'string', 'min:1', 'max:5000'],
            'phoneNumber' => ['required'],
            'Whatsapp_number' => ['required'],
            'address' => ['required', 'string', 'min:1', 'max:500'],
            'website_address' => ['nullable', 'string', 'min:1', 'max:255'],
            'logo_mitra' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:1024'],
            'status' => ['required', 'integer'],
        ]);

        // Store uploaded files
        $imagePath = $request->file('logo_mitra')->store('uploads/partner-images', 'public');

        // Create a new Partners associated with the user
        Mitra::create([
            'partner_name' => $validatedData['partner_name'],
            'email' => $validatedData['email'],
            'description' => $validatedData['description'],
            'phone_number' => $validatedData['phoneNumber'],
            'Whatsapp_number' => $validatedData['Whatsapp_number'],
            'address' => $validatedData['address'],
            'website_address' => $validatedData['website_address'],
            'image_url' => $imagePath,
            'status' => $validatedData['status'],
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'User berhasil ditambahkan'
        ], 201);
    }

    # Update Partner
    public function updatePartner(Request $request)
    {
        $validatedData = $request->validate([
           'partner_name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email', 'min:1', 'max:255'],
            'description' => ['required', 'string', 'min:1', 'max:5000'],
            'phoneNumber' => ['required'],
            'Whatsapp_number' => ['required'],
            'address' => ['required', 'string', 'min:1', 'max:500'],
            'website_address' => ['nullable', 'string', 'min:1', 'max:255'],
            'logo_mitra' => ['file', 'mimes:jpg,jpeg,png', 'max:1024'],
            'status' => ['required', 'integer'],
        ]);

        // Ambil data mitra yang akan diupdate
        $mitra = Mitra::findOrFail($request->id);

        // Perbarui detail mitra
        $mitra->partner_name = $validatedData['partner_name'];
        $mitra->email = $validatedData['email'];
        $mitra->description = $validatedData['description'];
        $mitra->phone_number = $validatedData['phoneNumber'];
        $mitra->Whatsapp_number = $validatedData['Whatsapp_number'];
        $mitra->address = $validatedData['address'];
        $mitra->website_address = $validatedData['website_address'];
        $mitra->status = $validatedData['status'];  

        // Proses penyimpanan logo mitra
        if ($request->hasFile('logo_mitra')) {
            if ($mitra->image_url && Storage::disk('public')->exists($mitra->image_url)) {
                Storage::disk('public')->delete($mitra->image_url);
            }
            $mitra->image_url = $request->file('logo_mitra')->store('uploads/logo_mitra', 'public');
        }
        
        $mitra->save();

        return response()->json([
            'success' => true,
            'msg' => 'User berhasil diedit'
        ], 201);
    }

    # Delete Partner
    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:users,id']
        ]);

        $mitra = Mitra::findOrFail($request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'User berhasil dihapus'
        ]);
    }

     public function show(Mitra $mitra)
    {
        return view('admin.masterData.partner.show')->with('mitra', $mitra);
    }


    #EXPORT EXCEL FUNCTION
    public function exportExcel(Request $request)
    {
        $status = $request->query('status', 'Aktif');
        $mitras = Mitra::where('status', $status)->get();

        return Excel::download(new PartnersExport($mitras), 'users.xlsx');
    }

    public function updatePartnerStatus(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:mitras,id']
        ]);

        $mitra = Mitra::findOrFail($request->id);

        $currStatus = $mitra->status;

        // Toggle faq status based on current status
        if ($currStatus == 0) {
            $mitra->status = 1;
        } elseif($currStatus == 1) {
            $mitra->status = 0;
        }

        $mitra->save();

        return response()->json([
            'success' => true,
            'msg' => 'Status berhasil diupdate'
        ]);
    }

    # Partner Edit
    public function editPartner(Mitra $mitra)
    {
        return view('admin.masterData.partner.edit')->with('mitra', $mitra);
    }

    # Periode
    # Periode Index
    public function PeriodIndex ()
    {
        $periodes = Periode::all();
        $totalPeriods = Periode::count();
        $totalActivePeriods = Periode::where('status', '=', '1' )->count();
        $totalNonActivePeriods = Periode::where('status', '=', '0' )->count();
        return view('admin.masterData.periode.index')->with([
            'periodes' => $periodes,
            'totalPeriods' => $totalPeriods,
            'totalActivePeriods' => $totalActivePeriods,
            'totalNonActivePeriods' => $totalNonActivePeriods,
        ]); 
    }

    # Periode Create

    public function createPeriod()
    {
        return view('admin.masterData.periode.create');
    }

    # Periode Store
    public function storePeriod(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'integer']
        ]);

        $periodName = $validated['name'];


        // Cek apakah periode sudah ada
        $periodExist = Periode::where('name', $periodName)->first();

        if ($periodExist) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => ['Periode sudah tercatat']
                ],
                400
            );
        }

        // Add new data
        Periode::create([
            'name' => $periodName, 
            'start_date' => $validated['start_date'], 
            'end_date' => $validated['end_date'],
            'status' => $validated['status']
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data periode berhasil ditambah'
        ], 201);
    }

    # Periode Edit
    public function editPeriod(Periode $periode)
    {
        return view('admin.masterData.periode.edit')->with('periode', $periode);
    }

    # Periode Update
    public function updatePeriod(Request $request)
    {
        $validated = $request->validate([
            'periodId' => ['required', 'numeric', 'exists:periodes,id'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'integer']
        ]);


        $id = $validated['periodId'];
        $periodName = $validated['name'];
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $status = $validated['status'];

        $periodExist = Periode::findOrFail($id);

        // Add new data
        $periodExist->name = $periodName;
        $periodExist->start_date = $startDate;
        $periodExist->end_date = $endDate;
        $periodExist->status = $status;
        $periodExist->save();

        return response()->json([
            'success' => true,
            'msg' => 'Data periode berhasil diedit'
        ], 201);
    }

    # Periode Delete
    public function deletePeriod(Request $request)
    {
        $validated = $request->validate([
            'periodId' => ['required', 'numeric', 'exists:faculties,id'],
        ]);

        $id = $validated['periodId'];

        Faculty::destroy($id);

        return response()->json([
            'success' => true,
            'msg' => 'Data periode berhasil dihapus'
        ], 201);
    }

    # Periode update status
    public function updatePeriodStatus(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:mitras,id']
        ]);

        $period = Periode::findOrFail($request->id);

        $currStatus = $period->status;

        // Toggle faq status based on current status
        if ($currStatus == 0) {
            $period->status = 1;
        } elseif($currStatus == 1) {
            $period->status = 0;
        }

        $period->save();

        return response()->json([
            'success' => true,
            'msg' => 'Status berhasil diupdate'
        ]);
    }

}
