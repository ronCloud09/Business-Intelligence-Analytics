<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single message (user or assistant) in a chatbot conversation.
 *
 * @property int $id
 * @property string $session_id
 * @property int|null $user_id
 * @property string $role
 * @property string $message
 * @property bool $used_ai
 */
#[Fillable(['session_id', 'user_id', 'role', 'message', 'used_ai'])]
class AIConversation extends Model
{
    protected $table = 'ai_conversations';
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'used_ai' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId)->orderBy('created_at');
    }
}
