<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GhnErrorHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);
            
            // Check if this is a GHN API response
            if ($request->is('ghn/*')) {
                $content = $response->getContent();
                $data = json_decode($content, true);
                
                // Log GHN API calls for debugging
                Log::info('GHN API Call', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'params' => $request->all(),
                    'response' => $data
                ]);
                
                // Handle common GHN errors
                if (isset($data['success']) && !$data['success']) {
                    $this->logGhnError($request, $data);
                }
            }
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('GHN Middleware Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            // Return user-friendly error for GHN endpoints
            if ($request->is('ghn/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra với dịch vụ giao hàng. Vui lòng thử lại sau.'
                ], 500);
            }
            
            throw $e;
        }
    }
    
    /**
     * Log GHN specific errors
     */
    private function logGhnError(Request $request, array $data)
    {
        $errorMessage = $data['message'] ?? 'Unknown GHN error';
        $errorCode = $data['code'] ?? 'UNKNOWN';
        
        Log::warning('GHN API Error', [
            'endpoint' => $request->path(),
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
            'request_data' => $request->all()
        ]);
        
        // Send notification for critical errors
        if (in_array($errorCode, ['TOKEN_INVALID', 'SHOP_NOT_FOUND'])) {
            // Could send email/Slack notification to admin
            Log::critical('Critical GHN Error', [
                'error_code' => $errorCode,
                'error_message' => $errorMessage
            ]);
        }
    }
}
