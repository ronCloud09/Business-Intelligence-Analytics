<?php
namespace App\Http\Controllers;

use App\Services\AI\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    public function __construct(protected ChatService $chatService)
    {
    }

    /**
     * POST /nexora-ai/chat  { "message": "...", "session_id": "..." (optional) }
     *
     * A plain JSON endpoint so the chatbot can be tested with curl/Postman
     * before Package 5 adds the chat widget UI to ai-insights.blade.php —
     * at that point the widget calls this exact same endpoint, nothing
     * here needs to change.
     */
    public function respond(Request $request): JsonResponse
    {
        // FIX: validation now runs BEFORE the try/catch. Previously it was
        // inside the try block, so a ValidationException (which implements
        // \Throwable) was caught by the generic catch below and turned into
        // a confusing 500 instead of Laravel's normal 422 field-errors response.
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'session_id' => ['nullable', 'string', 'max:100'],
        ]);

        try {

            $sessionId = $validated['session_id'] ?? $request->session()->getId();

            $result = $this->chatService->ask(
                sessionId: $sessionId,
                message: $validated['message'],
                userId: $request->user()?->id,
            );

            return response()->json($result);

        } catch (\Throwable $e) {

            // FIX: no longer returns $e->getMessage()/file/line to the client —
            // this endpoint has no auth gate, so leaking internal exception
            // text and server file paths to anonymous callers was an
            // information-disclosure risk. Full detail still goes to the log.
            \Log::error('[NexoraAI] AIChatController::respond failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Nexora AI is temporarily unavailable. Please try again in a moment.',
            ], 500);

        }
    }
}