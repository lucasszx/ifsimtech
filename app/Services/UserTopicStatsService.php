<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\UserTopicStat;

class UserTopicStatsService
{
    /**
     * Atualiza (ou cria) estatísticas históricas por tópico
     * para todas as respostas deste attempt.
     */
    public function updateFromAttempt(Attempt $attempt): void
    {
        $userId = $attempt->user_id;

        // Garante que answers, questions e topics estão carregados
        $attempt->loadMissing('answers.question.topics');

        foreach ($attempt->answers as $answer) {
            $question = $answer->question;

            // Se a questão tem tópicos associados
            if ($question->topics->count() > 0) {
                foreach ($question->topics as $topic) {
                    $this->updateOneTopic(
                        userId: $userId,
                        topicId: $topic->id,
                        isCorrect: (bool) $answer->is_correct
                    );
                }
            } else {
                // Aqui você decide: quer ignorar "Sem tópico" no histórico?
                // Eu recomendo IGNORAR, ou criar um tópico "genérico" no banco.
                // Por enquanto, vamos ignorar questões sem tópico:
                continue;
            }
        }
    }

    protected function updateOneTopic(int $userId, int $topicId, bool $isCorrect): void
    {
        // Busca ou cria o registro
        $stat = UserTopicStat::firstOrCreate(
            ['user_id' => $userId, 'topic_id' => $topicId],
            ['total_attempts' => 0, 'correct_attempts' => 0]
        );

        // Incrementa tentativas
        $stat->increment('total_attempts', 1);

        // Se acertou, incrementa acertos
        if ($isCorrect) {
            $stat->increment('correct_attempts', 1);
        }
    }
}
