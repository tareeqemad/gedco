<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Controller responsible for managing page direction (RTL/LTR) preferences.
 * Handles toggling and setting direction via session storage.
 */
class DirectionController extends Controller
{
    /**
     * Toggles the current page direction between RTL and LTR modes.
     * Returns JSON response for AJAX requests or redirects back otherwise.
     */
    public function toggle(Request $request)
    {
        $direction = Session::get('direction', 'rtl');
        $newDirection = $direction === 'rtl' ? 'ltr' : 'rtl';
        
        Session::put('direction', $newDirection);
        
        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'direction' => $newDirection
            ]);
        }
        
        return redirect()->back();
    }
    
    /**
     * Sets the page direction to specified value (rtl or ltr).
     * Validates input and stores preference in session for persistence.
     */
    public function set(Request $request, string $direction)
    {
        if (!in_array($direction, ['rtl', 'ltr'])) {
            $direction = 'rtl';
        }
        
        Session::put('direction', $direction);
        
        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'direction' => $direction
            ]);
        }
        
        return redirect()->back();
    }
}

