<?php
namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArticleSubmitted extends Notification
{
    use Queueable;

    public function __construct(public Article $article) {}

    public function via($notifiable): array { return ['database']; }

    public function toArray($notifiable): array
    {
        return [
            'type'        => 'article_submitted',
            'title'       => 'مقال جديد بانتظار الموافقة',
            'message'     => 'المقال "' . $this->article->title . '" بانتظار مراجعتك',
            'article_id'  => $this->article->id,
            'article_title' => $this->article->title,
            'author'      => $this->article->user->name,
            'url'         => route('dashboard.articles.index', ['status' => 'pending']),
        ];
    }
}
