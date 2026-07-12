namespace App\Http\Controllers;

use App\Models\DisasterDocumentation;
use Illuminate\Http\Request;

class VolunteerFileController extends Controller
{
    public function uploadDocumentation(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'attachment' => 'required|mimes:pdf,docx,png,jpg,jpeg|max:5120',
        ]);

        $file = $request->file('attachment');
        $extension = $file->getClientOriginalExtension();

        $folder = in_array($extension, ['pdf', 'docx']) ? 'disaster_docs' : 'disaster_images';
        $path = $file->store($folder, 'public');

        DisasterDocumentation::create([
            'title' => $request->title,
            'file_path' => $path,
            'file_type' => $extension,
        ]);

        return redirect()->back()->with('success', 'Dokumentasi File Kejadian Berhasil Disimpan!');
    }
}