@props(['content'])

@php
    // Parse JSON content if it's a string
    $editorData = is_string($content) ? json_decode($content, true) : $content;
    $blocks = $editorData['blocks'] ?? [];
@endphp

<div class="editorjs-content">
    @foreach($blocks as $block)
        @php
            $type = strtolower($block['type'] ?? 'paragraph');
            $data = $block['data'] ?? [];
        @endphp

        @switch($type)
            @case('header')
                @php
                    $level = $data['level'] ?? 2;
                    $text = $data['text'] ?? '';
                @endphp
                @if($level == 1)
                    <h1 class="text-4xl font-bold mb-6 mt-8 text-gray-900 dark:text-white">
                        {!! $text !!}
                    </h1>
                @elseif($level == 2)
                    <h2 class="text-3xl font-bold mb-5 mt-7 text-gray-900 dark:text-white">
                        {!! $text !!}
                    </h2>
                @elseif($level == 3)
                    <h3 class="text-2xl font-semibold mb-4 mt-6 text-gray-900 dark:text-white">
                        {!! $text !!}
                    </h3>
                @elseif($level == 4)
                    <h4 class="text-xl font-semibold mb-3 mt-5 text-gray-900 dark:text-white">
                        {!! $text !!}
                    </h4>
                @elseif($level == 5)
                    <h5 class="text-lg font-semibold mb-3 mt-4 text-gray-900 dark:text-white">
                        {!! $text !!}
                    </h5>
                @else
                    <h6 class="text-base font-semibold mb-2 mt-3 text-gray-900 dark:text-white">
                        {!! $text !!}
                    </h6>
                @endif
                @break

            @case('paragraph')
                @php
                    $text = $data['text'] ?? '';
                @endphp
                @if(!empty($text))
                    <p class="text-gray-700 dark:text-gray-300 mb-4 leading-relaxed text-lg">
                        {!! $text !!}
                    </p>
                @endif
                @break

            @case('list')
                @php
                    $style = $data['style'] ?? 'unordered';
                    $items = $data['items'] ?? [];
                    
                    if (!function_exists('renderEditorJsList')) {
                        function renderEditorJsList($items, $style) {
                            $html = '';
                            foreach($items as $item) {
                                $content = is_array($item) ? ($item['content'] ?? '') : $item;
                                $nestedItems = is_array($item) ? ($item['items'] ?? []) : [];
                                
                                $html .= '<li class="leading-relaxed">' . $content;
                                if (!empty($nestedItems)) {
                                    $tag = $style === 'ordered' ? 'ol' : 'ul';
                                    $class = $style === 'ordered' ? 'list-decimal' : 'list-disc';
                                    $html .= '<' . $tag . ' class="' . $class . ' list-inside mt-2 space-y-2 ml-4">';
                                    $html .= renderEditorJsList($nestedItems, $style);
                                    $html .= '</' . $tag . '>';
                                }
                                $html .= '</li>';
                            }
                            return $html;
                        }
                    }
                @endphp
                @if($style === 'ordered')
                    <ol class="list-decimal list-inside mb-6 space-y-2 text-gray-700 dark:text-gray-300 text-lg ml-4">
                        {!! renderEditorJsList($items, $style) !!}
                    </ol>
                @else
                    <ul class="list-disc list-inside mb-6 space-y-2 text-gray-700 dark:text-gray-300 text-lg ml-4">
                        {!! renderEditorJsList($items, $style) !!}
                    </ul>
                @endif
                @break

            @case('linkTool')
                @php
                    $link = $data['link'] ?? '';
                    $meta = $data['meta'] ?? [];
                    $title = $meta['title'] ?? $link;
                    $description = $meta['description'] ?? '';
                    $image = $meta['image']['url'] ?? '';
                @endphp
                <div class="mb-6">
                    <a href="{{ $link }}" target="_blank" rel="nofollow noopener" class="flex flex-col md:flex-row border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden hover:border-primary transition-colors bg-white dark:bg-gray-800 shadow-sm group">
                        @if($image)
                            <div class="md:w-48 h-40 md:h-auto overflow-hidden shrink-0">
                                <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif
                        <div class="p-4 flex flex-col justify-center">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1 group-hover:text-primary transition-colors">{{ $title }}</h4>
                            @if($description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">{{ $description }}</p>
                            @endif
                            <span class="text-xs text-primary flex items-center gap-1 font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                {{ parse_url($link, PHP_URL_HOST) }}
                            </span>
                        </div>
                    </a>
                </div>
                @break

            @case('raw')
                <div class="raw-html-block mb-6">
                    {!! $data['html'] ?? '' !!}
                </div>
                @break

            @case('image')
                @php
                    $url = $data['file']['url'] ?? '';
                    $caption = $data['caption'] ?? '';
                    $withBorder = $data['withBorder'] ?? false;
                    $withBackground = $data['withBackground'] ?? false;
                    $stretched = $data['stretched'] ?? false;
                @endphp
                <figure class="mb-8 {{ $stretched ? '' : 'max-w-3xl mx-auto' }}">
                    <div class="rounded-xl overflow-hidden {{ $withBorder ? 'border-2 border-gray-200 dark:border-gray-700' : '' }} {{ $withBackground ? 'bg-gray-100 dark:bg-gray-800 p-4' : '' }}">
                        <img src="{{ $url }}" 
                             alt="{{ $caption }}" 
                             class="w-full h-auto {{ $stretched ? '' : 'mx-auto' }}"
                             loading="lazy">
                    </div>
                    @if($caption)
                        <figcaption class="text-center text-sm text-gray-600 dark:text-gray-400 mt-3 italic">
                            {!! $caption !!}
                        </figcaption>
                    @endif
                </figure>
                @break

            @case('quote')
                @php
                    $text = $data['text'] ?? '';
                    $caption = $data['caption'] ?? '';
                    $alignment = $data['alignment'] ?? 'left';
                @endphp
                <blockquote class="border-l-4 border-primary pl-6 py-2 mb-6 italic bg-gray-50 dark:bg-gray-800/50 rounded-r-lg text-{{ $alignment }}">
                    <p class="text-xl text-gray-800 dark:text-gray-200 mb-2">
                        {!! $text !!}
                    </p>
                    @if($caption)
                        <cite class="text-sm text-gray-600 dark:text-gray-400 not-italic">
                            — {!! $caption !!}
                        </cite>
                    @endif
                </blockquote>
                @break

            @case('code')
                @php
                    $code = $data['code'] ?? '';
                @endphp
                <pre class="bg-gray-900 text-gray-100 rounded-xl p-6 mb-6 overflow-x-auto"><code class="text-sm font-mono">{{ $code }}</code></pre>
                @break

            @case('delimiter')
                <div class="flex items-center justify-center my-10">
                    <div class="flex space-x-2">
                        <span class="w-2 h-2 bg-gray-400 dark:bg-gray-600 rounded-full"></span>
                        <span class="w-2 h-2 bg-gray-400 dark:bg-gray-600 rounded-full"></span>
                        <span class="w-2 h-2 bg-gray-400 dark:bg-gray-600 rounded-full"></span>
                    </div>
                </div>
                @break

            @case('warning')
                @php
                    $title = $data['title'] ?? 'Warning';
                    $message = $data['message'] ?? '';
                @endphp
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-6 mb-6 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500 mr-3 shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="font-bold text-yellow-800 dark:text-yellow-300 mb-1">
                                {!! $title !!}
                            </h4>
                            <p class="text-yellow-700 dark:text-yellow-400">
                                {!! $message !!}
                            </p>
                        </div>
                    </div>
                </div>
                @break

            @case('table')
                @php
                    $content = $data['content'] ?? [];
                    $withHeadings = $data['withHeadings'] ?? false;
                @endphp
                <div class="mb-8 overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        @if($withHeadings && count($content) > 0)
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    @foreach($content[0] as $cell)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            {!! $cell !!}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach(array_slice($content, 1) as $row)
                                    <tr>
                                        @foreach($row as $cell)
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {!! $cell !!}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($content as $row)
                                    <tr>
                                        @foreach($row as $cell)
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {!! $cell !!}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
                @break

            @case('embed')
                @php
                    $service = $data['service'] ?? '';
                    $embed = $data['embed'] ?? '';
                    $caption = $data['caption'] ?? '';
                    $width = $data['width'] ?? 0;
                    $height = $data['height'] ?? 0;
                @endphp
                <figure class="mb-8">
                    <div class="relative overflow-hidden rounded-xl" style="padding-bottom: 56.25%;">
                        <iframe src="{{ $embed }}" 
                                class="absolute top-0 left-0 w-full h-full"
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                    @if($caption)
                        <figcaption class="text-center text-sm text-gray-600 dark:text-gray-400 mt-3">
                            {!! $caption !!}
                        </figcaption>
                    @endif
                </figure>
                @break

            @case('checklist')
                @php
                    $items = $data['items'] ?? [];
                @endphp
                <ul class="mb-6 space-y-3">
                    @foreach($items as $item)
                        @php
                            $checked = $item['checked'] ?? false;
                            $text = $item['text'] ?? '';
                        @endphp
                        <li class="flex items-start">
                            <span class="flex items-center h-6 mr-3">
                                @if($checked)
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </span>
                            <span class="text-gray-700 dark:text-gray-300 text-lg {{ $checked ? 'line-through opacity-60' : '' }}">
                                {!! $text !!}
                            </span>
                        </li>
                    @endforeach
                </ul>
                @break

            @case('attaches')
                @php
                    $file = $data['file'] ?? [];
                    $title = $data['title'] ?? '';
                @endphp
                <div class="mb-6 border border-gray-200 dark:border-gray-700 rounded-xl p-6 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <a href="{{ $file['url'] ?? '#' }}" class="flex items-center gap-4" download>
                        <div class="shrink-0">
                            <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="grow">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $title ?: ($file['name'] ?? 'Download File') }}
                            </h4>
                            @if(isset($file['size']))
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ number_format($file['size'] / 1024, 2) }} KB
                                </p>
                            @endif
                        </div>
                        <div class="shrink-0">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </div>
                    </a>
                </div>
                @break

            @default
                {{-- Fallback for unknown block types --}}
                <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Unsupported block type: <code class="text-xs bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded">{{ $type }}</code>
                    </p>
                </div>
        @endswitch
    @endforeach
</div>
