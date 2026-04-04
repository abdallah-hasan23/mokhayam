<?php
namespace App\Notifications;

use App\Models\Article;
use App\Models\ArticleVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewVersionSubmitted extends Notification
{
    use Queueable;

    public function __construct(public Article $article, public ArticleVersion $version) {}

    public function via($notifiable): array { return ['database']; }

    public function toArray($notifiable): array
    {
        return [
            'type'          => 'version_submitted',
            'title'         => 'نسخة جديدة بانتظار الموافقة',
            'message'       => 'تم تعديل المقال "' . $this->article->title . '" وبانتظار مراجعتك',
            'article_id'    => $this->article->id,
            'version_id'    => $this->version->id,
            'article_title' => $this->article->title,
            'author'        => $this->version->submitter->name,
            'url'           => route('dashboard.articles.versions', $this->article->id),
        ];
    }
}
