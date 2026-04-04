<?php
namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArticleStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Article $article, public string $newStatus) {}

    public function via($notifiable): array { return ['database']; }

    public function toArray($notifiable): array
    {
        $isApproved = $this->newStatus === 'published';
        return [
            'type'          => 'article_' . $this->newStatus,
            'title'         => $isApproved ? 'تمت الموافقة على مقالك' : 'تم رفض مقالك',
            'message'       => $isApproved
                ? 'تمت الموافقة على مقالك "' . $this->article->title . '" ونشره'
                : 'تم رفض مقالك "' . $this->article->title . '"',
            'article_id'    => $this->article->id,
            'article_title' => $this->article->title,
            'url'           => route('dashboard.articles.index'),
        ];
    }
}
