<?

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class TestController extends Controller
{
    public function getSession(Request $request)
    {
        $request->session()->put("userName", "VOID001");
        return redirect()->route('home');
    }

    public function destroySession(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('home');
    }
}