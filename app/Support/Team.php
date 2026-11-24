<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Team-related rules for the evaluation forms.
 *
 * The "Management" question category only applies to people who have a team,
 * i.e. at least one user has them as supervisor (users.supervisor_id).
 */
class Team
{
    public const MANAGEMENT_CATEGORY = 'Management';

    public static function hasTeam($userId): bool
    {
        return $userId !== null
            && DB::table('users')->where('supervisor_id', $userId)->exists();
    }

    /**
     * IDs of the questions the given (evaluated) user must not see or be scored on.
     *
     * @return int[]
     */
    public static function hiddenQuestionIds($userId): array
    {
        if (self::hasTeam($userId)) {
            return [];
        }

        return DB::table('questions')
            ->join('categories', 'categories.id', '=', 'questions.categorie_id')
            ->where('categories.name', self::MANAGEMENT_CATEGORY)
            ->pluck('questions.question_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Drop submitted answers to questions hidden for this user.
     */
    public static function filterResponses($userId, ?array $responses): array
    {
        $hidden = self::hiddenQuestionIds($userId);

        return array_diff_key($responses ?? [], array_flip($hidden));
    }
}
