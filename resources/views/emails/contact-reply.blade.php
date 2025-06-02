<p>Xin chào {{ $contact->name }},</p>

<p>Chúng tôi đã nhận được phản hồi từ bạn. Dưới đây là nội dung phản hồi từ quản trị viên:</p>

<blockquote style="border-left: 4px solid #ccc; padding-left: 10px;">
    {!! nl2br(e($messageContent)) !!}
</blockquote>

<p>Trân trọng,<br>Đội ngũ quản trị viên website.</p>
