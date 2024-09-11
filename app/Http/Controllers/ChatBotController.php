<?php

namespace App\Http\Controllers;

use App\Models\ConversationHistory;
use App\Models\ChatRequest;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;

class ChatBotController extends Controller
{

    public function sendMonthlyReport()
    {
        $chatRequest = ChatRequest::first();

        if ($chatRequest) {
            $monthlyRequests = $chatRequest->monthly_requests ?? [];
            $currentMonth = now()->format('Y-m');
            $currentMonthRequests = $monthlyRequests[$currentMonth] ?? 0;

            $pdfContent = view('monthly_report', compact('currentMonthRequests'))->render();

            $mpdf = new Mpdf();
            $mpdf->WriteHTML($pdfContent);
            $pdfOutput = $mpdf->Output('', 'S');

            Mail::send([], [], function ($message) use ($pdfOutput) {
                $message->to('valenmolina987@gmail.com')
                        ->subject('Reporte Mensual de Requests')
                        ->attachData($pdfOutput, 'reporte_mensual.pdf', [
                            'mime' => 'application/pdf',
                        ]);
            });
        }
    }

    public function downloadMonthlyReport()
    {
        $chatRequest = ChatRequest::find(6);
        $currentMonth = now()->format('Y-m');
        $currentMonthRequests = $chatRequest->monthly_requests[$currentMonth] ?? 0;

        $pdfContent = view('monthly_report', compact('currentMonthRequests'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($pdfContent);

        return $mpdf->Output('reporte_mensual.pdf', 'D'); 
    }


    public function showUpdateContextForm()
    {
        $chatRequest = ChatRequest::find(6);
        $context = $chatRequest ? $chatRequest->context : '';
        $totalRequest = $chatRequest ? $chatRequest->total_request : 0;

        return view('update_context', compact('context', 'totalRequest'));
    }
    
    public function updateContext(Request $request)
    {
        $newContext = $request->input('context');

        $chatRequest = ChatRequest::find(6);
        $chatRequest->context = $newContext;
        $chatRequest->save();

        return redirect()->route('update.context.form')->with('success', 'Context updated successfully');
    }

    private function getBusinessContext()
    {
        $chatRequest = ChatRequest::find(6);
        return $chatRequest ? $chatRequest->context : 'Default business context';
    }

    public function sendChat($mensajeCliente, $idLead)
    {
        $userMessage = $mensajeCliente;

        $conversation = ConversationHistory::firstOrCreate(
            ['lead_id' => $idLead],
            ['history' => []]
        );

        $conversationHistory = $conversation->history;

        if (trim(strtolower($userMessage)) === 'contactame con un asesor') {
            $conversation->delete();
            return '¡Gracias por tu interés! Un asesor se pondrá en contacto contigo pronto.';
        }

        $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];

        $body = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Vas a ser un chatbot que va a recibir preguntas de un negocio y tu tarea es responderles la informacion hasta que quieran hablar con un asesor o adquirir un servicio" . $this->getBusinessContext()
                ]
            ]
        ];

        foreach ($conversationHistory as $interaction) {
            if (!empty($interaction['role']) && !empty($interaction['content'])) {
                $body['messages'][] = ['role' => $interaction['role'], 'content' => $interaction['content']];
            }
        }

        $result = OpenAI::chat()->create($body);

        $response = $result->choices[0]->message->content;

        $conversationHistory[] = ['role' => 'assistant', 'content' => $response];

        $conversation->update(['history' => $conversationHistory]);

        $chatRequest = ChatRequest::find(6);
        if ($chatRequest) {
            $chatRequest->total_request++;

            $currentMonth = now()->format('Y-m');
            $monthlyRequests = $chatRequest->monthly_requests ?? [];
            if (!isset($monthlyRequests[$currentMonth])) {
                $monthlyRequests[$currentMonth] = 0;
            }
            $monthlyRequests[$currentMonth]++;
            $chatRequest->monthly_requests = $monthlyRequests;

            $chatRequest->save();
        }

        return $response;
    }

    public function getInfoKommo(Request $request)
    {
        $id = $request['leads']['add'][0]['id'];

        $kommo = new KommoController();
        $mensajeCliente = $kommo->getContact($id);

        $respuestaChatbot = $this->sendChat($mensajeCliente, $id);

        $kommo->responseChatBot($id, $respuestaChatbot);
    }
}
?>
