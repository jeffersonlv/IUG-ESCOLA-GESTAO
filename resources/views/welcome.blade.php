@extends('app')

@section('title', 'Instituto Ulysses Guimarães — Gestão Pública')

@section('content')

{{-- ══════════════════════════════════════════
     HERO BANNER
══════════════════════════════════════════ --}}
<div style="position:relative; width:100%; overflow:hidden; max-height:480px;">
    <img src="/images/banner.jpg" alt="Banner" style="width:100%; object-fit:cover; display:block; max-height:480px; filter:brightness(0.55);">
    <div style="position:absolute; inset:0; display:flex; align-items:center;">
        <div class="container">
            <div style="max-width:600px;">
                <h1 style="font-family:'Montserrat',sans-serif; font-weight:700; font-size:clamp(1.5rem,4vw,2.4rem); color:#fff; margin-bottom:0.5rem; line-height:1.2;">
                    {{ $configs['banner_titulo'] ?? 'Capacitação e Treinamentos' }}
                </h1>
                @if(!empty($configs['publico_alvo']))
                <p style="color:rgba(255,255,255,0.95); font-size:clamp(1.1rem,3vw,1.6rem); margin-bottom:1.5rem; line-height:1.6;">
                    <strong style="color:#E8600A; font-size:clamp(1.1rem,3vw,1.6rem);">Público-alvo:</strong><br>
                    {{ $configs['publico_alvo'] }}
                </p>
                @endif
                <a href="#cursos" class="btn btn-primary px-4 py-2 fw-bold">Ver Cursos ↓</a>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     SOBRE NÓS
══════════════════════════════════════════ --}}
@if(!empty($configs['sobre_texto']))
<section id="sobre" style="background:#fff; padding:4rem 0;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="accent-bar"></div>
                <h2 style="font-family:'Montserrat',sans-serif; font-weight:700; color:#1A2B5F; font-size:1.5rem; margin-bottom:1rem;">Sobre Nós</h2>
                <p style="color:#555; font-size:1rem; line-height:1.8;">{{ $configs['sobre_texto'] }}</p>
            </div>
            <div class="col-lg-5 text-center">
                <img src="/images/sobre.png" alt="Instituto" style="max-width:100%; height:auto; border-radius:12px;">
            </div>
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════
     SETOR DA TRANSPARÊNCIA
══════════════════════════════════════════ --}}
@if($documentos->count())
<section id="documentos" style="background:#F0F2F8; padding:4rem 0;">
    <div class="container">
        <div class="accent-bar"></div>
        <h2 style="font-family:'Montserrat',sans-serif; font-weight:700; color:#1A2B5F; font-size:1.5rem; margin-bottom:0.5rem;">Setor da Transparência</h2>
        <p class="text-muted mb-4" style="font-size:0.9rem;">Documentos e certificações disponíveis para consulta pública.</p>

        <div class="row g-4">
            @foreach($documentos as $doc)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    @if($doc->arquivo_pdf)
                    @php $docUrl = \Illuminate\Support\Facades\Storage::url('documentos/' . $doc->arquivo_pdf); @endphp
                    <div style="border-radius:8px 8px 0 0; overflow:hidden; border-bottom:1px solid #DDE1EB;">
                        <iframe src="{{ $docUrl }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                style="width:100%; height:200px; display:block; border:none;"
                                loading="lazy"></iframe>
                    </div>
                    @else
                    <div style="height:200px; background:linear-gradient(135deg,#1A2B5F,#243a7a); border-radius:8px 8px 0 0; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:8px;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="15" y2="17"/></svg>
                        <span style="color:rgba(255,255,255,0.4); font-size:0.7rem; letter-spacing:1px; text-transform:uppercase;">PDF</span>
                    </div>
                    @endif
                    <div class="card-body d-flex flex-column p-3">
                        <p class="fw-semibold mb-3" style="color:#1A2B5F; font-size:0.875rem; flex:1;">{{ $doc->nome }}</p>
                        @if($doc->arquivo_pdf)
                        <a href="{{ route('download.documento', $doc->id) }}" class="btn btn-primary btn-sm w-100">⬇ Download PDF</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════
     CURSOS
══════════════════════════════════════════ --}}
<section id="cursos" style="background:#fff; padding:4rem 0;">
    <div class="container">
        <div class="accent-bar"></div>
        <h2 style="font-family:'Montserrat',sans-serif; font-weight:700; color:#1A2B5F; font-size:1.5rem; margin-bottom:2rem;">Cursos e Capacitações</h2>

        <div class="row g-4">
        @forelse($cursos as $curso)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border-top:4px solid #E8600A;">
                <div class="card-body p-4 d-flex flex-column">
                    <p style="color:#E8600A; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:0.4rem;">
                        📍 {{ $curso->local }}
                    </p>
                    <h5 style="font-family:'Montserrat',sans-serif; font-weight:700; color:#1A2B5F; font-size:1rem; margin-bottom:0.5rem; flex:1;">
                        {{ $curso->titulo }}
                    </h5>
                    <p class="text-muted mb-3" style="font-size:0.85rem;">
                        📅
                        @if($curso->data_inicio->format('m/Y') === $curso->data_fim->format('m/Y'))
                            {{ $curso->data_inicio->format('d') }} a {{ $curso->data_fim->format('d') }}/{{ $curso->data_fim->format('m/Y') }}
                        @else
                            {{ $curso->data_inicio->format('d/m/Y') }} — {{ $curso->data_fim->format('d/m/Y') }}
                        @endif
                    </p>

                    {{-- Preview PDF inline: respeita flyer_principal --}}
                    @if($curso->folder_pdf || $curso->arquivo_pdf)
                    @php
                        $principal = $curso->flyer_principal;
                        if (!$principal) {
                            $principal = $curso->folder_pdf ? 'gerado' : 'upload';
                        }
                        if ($principal === 'gerado' && $curso->folder_pdf) {
                            $pdfUrl = '/storage/' . $curso->folder_pdf;
                        } elseif ($principal === 'upload' && $curso->arquivo_pdf) {
                            $pdfUrl = '/storage/cursos/' . $curso->arquivo_pdf;
                        } elseif ($curso->folder_pdf) {
                            $pdfUrl = '/storage/' . $curso->folder_pdf;
                        } else {
                            $pdfUrl = '/storage/cursos/' . $curso->arquivo_pdf;
                        }
                    @endphp
                    <div class="mt-2 mb-3" style="border-radius:6px; overflow:hidden; border:1px solid #DDE1EB;">
                        <iframe src="{{ $pdfUrl }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                style="width:100%; height:220px; display:block; border:none;"
                                loading="lazy"></iframe>
                    </div>
                    <a href="{{ route('download.flyer', $curso->id) }}" class="btn btn-primary btn-sm w-100 mt-auto">
                        ⬇ Download Flyer
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col"><p class="text-muted">Nenhum curso disponível no momento.</p></div>
        @endforelse
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     CONTATO
══════════════════════════════════════════ --}}
<section id="contato" style="background:#1A2B5F; padding:4rem 0;">
    <div class="container">
        <div class="text-center mb-4">
            <h2 style="font-family:'Montserrat',sans-serif; font-weight:700; color:#fff; font-size:1.6rem;">Alguma dúvida? Entre em contato conosco.</h2>
            <p style="color:rgba(255,255,255,0.7); font-size:0.95rem;">Estamos prontos para te atender.</p>
        </div>

        @if(!empty($configs['whatsapp']))
        <div class="text-center mb-5">
            <a href="https://wa.me/55{{ preg_replace('/\D/','',$configs['whatsapp']) }}"
               target="_blank"
               style="display:inline-flex; align-items:center; gap:10px; background:#25D366; color:#fff; font-weight:700; font-size:1rem; padding:1rem 2rem; border-radius:50px; text-decoration:none; box-shadow:0 4px 20px rgba(37,211,102,0.4); transition:transform 0.2s;"
               onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Clique para enviar uma mensagem no WhatsApp
            </a>
        </div>
        @endif

        <div class="row g-4 text-center justify-content-center">
            <div class="col-md-4">
                <p style="color:rgba(255,255,255,0.5); font-size:0.7rem; text-transform:uppercase; letter-spacing:1px; margin-bottom:0.5rem;">Endereço</p>
                <p style="color:#fff; font-size:0.875rem; line-height:1.9; margin:0;">
                    {!! nl2br(e($configs['endereco'] ?? 'Rua Q SDE Quadra 01 Conjunto E Lote, Nº04')) !!}
                </p>
            </div>
            <div class="col-md-3">
                <p style="color:rgba(255,255,255,0.5); font-size:0.7rem; text-transform:uppercase; letter-spacing:1px; margin-bottom:0.5rem;">Telefone</p>
                <p style="color:#fff; font-size:0.95rem; font-weight:600; margin:0;">{{ $configs['telefone'] ?? '(61) 98654-5280' }}</p>
            </div>
            <div class="col-md-4">
                <p style="color:rgba(255,255,255,0.5); font-size:0.7rem; text-transform:uppercase; letter-spacing:1px; margin-bottom:0.5rem;">E-mail</p>
                <a href="mailto:{{ $configs['email'] ?? 'contato@institutoulyssesguimaraes.com.br' }}"
                   style="color:#E8600A; font-size:0.9rem; text-decoration:none;">
                    {{ $configs['email'] ?? 'contato@institutoulyssesguimaraes.com.br' }}
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
