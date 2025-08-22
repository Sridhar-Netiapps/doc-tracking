<!-- resources/views/emails/form.blade.php -->

<div class="mb-3">
    <label for="sender" class="form-label">Sender <span class="text-danger">*</span></label>
    <input type="email" name="sender" class="form-control" value="{{ old('sender', $email->sender ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="to" class="form-label">To <span class="text-danger">*</span></label>
    <input type="email" name="to" class="form-control" value="{{ old('to', $email->to ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="cc" class="form-label">CC</label>
    <input type="text" name="cc" class="form-control" value="{{ old('cc', $email->cc ?? '') }}">
</div>

<div class="mb-3">
    <label for="bcc" class="form-label">BCC</label>
    <input type="text" name="bcc" class="form-control" value="{{ old('bcc', $email->bcc ?? '') }}">
</div>

<div class="mb-3">
    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
    <input type="text" name="subject" class="form-control" value="{{ old('subject', $email->subject ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
    <textarea name="message" class="form-control" rows="4" required>{{ old('message', $email->message ?? '') }}</textarea>
</div>

@if(isset($email))
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="draft" {{ $email->status == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="sent" {{ $email->status == 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="failed" {{ $email->status == 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
    </div>
@endif
