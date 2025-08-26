
<div class="mb-8 @if($errors->has('answers.'.$question->id)) border border-red-500 rounded-lg p-3 @endif" id="q-{{$question->id}}">
    <p class="mb-3 font-medium text-gray-700">{{ $question->order }}. {{ $question->text }}</p>
    <div class="grid grid-cols-5 gap-3">
        @php
            $options = [
                ['value' => 5, 'emoji' => '🤩', 'label' => 'Sangat Memuaskan'],
                ['value' => 4, 'emoji' => '😊', 'label' => 'Memuaskan'],
                ['value' => 3, 'emoji' => '😐', 'label' => 'Cukup Memuaskan'],
                ['value' => 2, 'emoji' => '😟', 'label' => 'Tidak Memuaskan'],
                ['value' => 1, 'emoji' => '😠', 'label' => 'Sangat Tdk Memuaskan'],
            ];
        @endphp

        @foreach ($options as $option)
        <label class="rating-option flex flex-col items-center p-3 border rounded-lg cursor-pointer @if(old('answers.'.$question->id) == $option['value']) selected @endif">
            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option['value'] }}" class="hidden" @if(old('answers.'.$question->id) == $option['value']) checked @endif>
            <span class="emoji-rating">{{ $option['emoji'] }}</span>
            <span class="text-xs text-gray-600 mt-1 text-center">{{ $option['label'] }}</span>
        </label>
        @endforeach
    </div>
</div>

