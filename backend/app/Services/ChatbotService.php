<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Handle incoming message from user
     */
    public function handleMessage($user, $message)
    {
        // 1. Pre-processing: Clean text
        $cleanedMessage = $this->preprocess($message);
        
        // 2. Intent Detection
        $intent = $this->detectIntent($cleanedMessage);
        
        $response = '';
        
        if ($intent) {
            // If simple request -> handle locally
            $response = $this->handleLocalIntent($intent);
        } else {
            // If complex -> send prompt to OpenAI API
            $response = $this->handleComplexRequest($cleanedMessage);
        }
        
        // 3. Post-processing / Guardrails validation
        // In this architecture, guardrails are also enforced at the prompt level
        $response = $this->postProcess($response);
        
        // 4. Logging
        ChatLog::create([
            'user_id' => $user->id,
            'message' => $message,
            'response' => $response,
            'intent' => $intent ?? 'complex'
        ]);
        
        return [
            'response' => $response,
            'intent' => $intent ?? 'complex'
        ];
    }
    
    private function preprocess($message)
    {
        // Convert to lowercase and trim
        return trim(strtolower($message));
    }
    
    private function detectIntent($message)
    {
        // Simple regex or keyword matching for local intents
        if (preg_match('/(cara|panduan|info).*(bayar|pembayaran)/i', $message)) {
            return 'pembayaran';
        }
        
        if (preg_match('/(jadwal|pelajaran|belajar|kelas)/i', $message)) {
            return 'jadwal';
        }
        
        if (preg_match('/(nilai|rapor|hasil|ujian)/i', $message)) {
            return 'nilai';
        }
        
        if (preg_match('/(halo|hai|salam|selamat)/i', $message)) {
            return 'greeting';
        }
        
        return null;
    }
    
    private function handleLocalIntent($intent)
    {
        switch ($intent) {
            case 'pembayaran':
                return "Untuk melakukan pembayaran, silakan navigasikan ke menu **Pembayaran** di dashboard Anda. Anda dapat membuat tagihan baru dan menyelesaikannya melalui virtual account atau metode lain yang tersedia.";
            case 'jadwal':
                return "Jadwal akademik Anda dapat diakses melalui menu **Jadwal Akademik**. Di sana Anda bisa melihat mata pelajaran, guru, dan ruangan untuk hari ini.";
            case 'nilai':
                return "Nilai Anda dapat dilihat pada menu **Nilai Akademik**. Pastikan semua tagihan sudah dilunasi jika Anda tidak dapat melihat nilai terbaru.";
            case 'greeting':
                return "Halo! Saya adalah Asisten Virtual Anda. Ada yang bisa saya bantu terkait pendaftaran, pembayaran, atau jadwal hari ini?";
            default:
                return "Maaf, saya tidak mengerti maksud Anda.";
        }
    }
    
    private function handleComplexRequest($message)
    {
        $apiKey = env('OPENAI_API_KEY');
        
        if (empty($apiKey)) {
            // Fallback if no API key is provided
            return "Maaf, fitur AI saat ini tidak tersedia (API Key belum dikonfigurasi). Silakan hubungi admin atau tanyakan hal yang lebih sederhana.";
        }

        $systemPrompt = "Anda adalah Customer Service Assistant untuk sistem informasi sekolah. " . 
                        "Tugas Anda: memandu user terkait fitur sistem (pembayaran, jadwal, nilai, dll). " . 
                        "PENTING: Jangan membuat query database, transaksi, atau aksi teknis. Anda hanya memandu. " .
                        "Jika ada pertanyaan di luar topik sekolah atau sistem informasi, tolak dengan sopan.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.3,
                'max_tokens' => 250,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? "Maaf, saya tidak dapat merespons saat ini.";
            } else {
                Log::error('OpenAI API Error: ' . $response->body());
                return "Terjadi kesalahan saat menghubungi server AI. Mohon coba lagi nanti.";
            }
        } catch (\Exception $e) {
            Log::error('OpenAI Exception: ' . $e->getMessage());
            return "Terjadi kesalahan pada sistem. Silakan coba lagi.";
        }
    }
    
    private function postProcess($response)
    {
        // Simple guardrail on the output
        $prohibitedWords = ['drop table', 'select * from', 'delete from', 'update users'];
        foreach ($prohibitedWords as $word) {
            if (stripos($response, $word) !== false) {
                return "Respons diblokir oleh sistem keamanan karena mengandung perintah tidak valid.";
            }
        }
        
        return $response;
    }
}
