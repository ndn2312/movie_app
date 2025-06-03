<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Hiển thị giao diện chat
     */
    public function index()
    {
        $chatHistory = [];
        if (Auth::check()) {
            $chatHistory = Chatbot::where('user_id', Auth::id())
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('chatbot.index', compact('chatHistory'));
    }

    /**
     * Xử lý tin nhắn từ người dùng và gọi API OpenAI để trả lời
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->message;

        try {
            $response = $this->getAIResponse($userMessage);

            // Lưu tin nhắn và câu trả lời vào database
            if (Auth::check()) {
                Chatbot::create([
                    'user_id' => Auth::id(),
                    'user_message' => $userMessage,
                    'bot_response' => $response
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $userMessage,
                'response' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('ChatBot API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $userMessage,
                'response' => 'Xin lỗi, tôi đang gặp sự cố kết nối. Chi tiết lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý việc gọi API ChatGPT để trả lời
     */
    private function getAIResponse($message)
    {
        // Thêm ngữ cảnh về các bộ phim trong hệ thống
        $context = $this->getMovieContext();

        // System message để định hướng AI
        $systemMessage = 'Bạn là trợ lý AI của trang web xem phim NdnPhim. ' .
            'Hãy trả lời câu hỏi về phim ảnh một cách thân thiện, ngắn gọn và chính xác. ' .
            'Chỉ trả lời những câu hỏi liên quan đến phim ảnh, giải trí và chủ đề liên quan. ' .
            'Từ chối lịch sự với các câu hỏi không phù hợp hoặc nhạy cảm. ' .
            'Sử dụng tiếng Việt để trả lời. ' .
            'Nếu được hỏi về phim cụ thể, hãy cung cấp thông tin từ ngữ cảnh nếu có.';

        // Prompt với ngữ cảnh
        $userPrompt = "Thông tin về các phim hiện có trong hệ thống: $context\n\nCâu hỏi: $message";

        $apiKey = env('OPENAI_API_KEY');

        // Kiểm tra API key
        if (empty($apiKey)) {
            Log::error('OpenAI API key không được cấu hình');
            throw new \Exception('API key chưa được cấu hình');
        }

        try {
            $response = Http::withOptions([
                'timeout' => 30,  // Timeout sau 30 giây
            ])->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',  // Có thể thay đổi thành gpt-4 nếu cần
                'messages' => [
                    ['role' => 'system', 'content' => $systemMessage],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => 0.7,    // Điều chỉnh độ sáng tạo (0-2)
                'max_tokens' => 500,     // Giới hạn độ dài câu trả lời
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0.6,   // Khuyến khích đa dạng chủ đề
            ]);

            if ($response->failed()) {
                $errorDetail = $response->json();
                Log::error('OpenAI API Error:', $errorDetail);

                // Xử lý lỗi theo mã HTTP
                if ($response->status() == 401) {
                    throw new \Exception('API key không hợp lệ');
                } elseif ($response->status() == 429) {
                    throw new \Exception('Vượt quá giới hạn yêu cầu API. Vui lòng thử lại sau.');
                }

                throw new \Exception($errorDetail['error']['message'] ?? 'Lỗi không xác định từ API');
            }

            $data = $response->json();

            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content'];
            } else {
                Log::error('OpenAI API response không hợp lệ:', $data);
                throw new \Exception('Phản hồi không hợp lệ từ API');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi khi gọi OpenAI API: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lấy thông tin về các bộ phim để cung cấp ngữ cảnh cho AI
     */
    private function getMovieContext()
    {
        try {
            $movies = Movie::select('id', 'title', 'origin_name', 'description', 'genre_id', 'country_id', 'year', 'content')
                ->with(['genre:id,title', 'country:id,title'])
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();

            $context = "Danh sách một số bộ phim trên hệ thống NdnPhim: \n";

            foreach ($movies as $index => $movie) {
                $genreNames = $movie->genre ? implode(', ', $movie->genre->pluck('title')->toArray()) : 'Chưa phân loại';
                $countryName = $movie->country ? $movie->country->title : 'Chưa xác định';

                $context .= ($index + 1) . ". {$movie->title} ({$movie->origin_name}), " .
                    "Năm: {$movie->year}, " .
                    "Thể loại: {$genreNames}, " .
                    "Quốc gia: {$countryName}\n";

                // Thêm mô tả ngắn nếu có
                if (!empty($movie->description)) {
                    $context .= "   Mô tả: " . substr($movie->description, 0, 100) . "...\n";
                }
            }

            return $context;
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo ngữ cảnh phim: ' . $e->getMessage());
            return "Không thể tải danh sách phim.";
        }
    }

    /**
     * Xóa lịch sử chat
     */
    public function clearHistory()
    {
        if (Auth::check()) {
            Chatbot::where('user_id', Auth::id())->delete();
            return redirect()->back()->with('success', 'Lịch sử chat đã được xóa.');
        }

        return redirect()->back()->with('error', 'Không thể xóa lịch sử chat.');
    }
}
