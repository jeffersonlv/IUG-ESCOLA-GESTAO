<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Curso;
use App\Models\SiteConfig;

class SiteDataSeeder extends Seeder
{
    public function run()
    {
        // ── Cursos ──
        Schema::disableForeignKeyConstraints();
        Curso::truncate();
        Schema::enableForeignKeyConstraints();

        $cursos = [
            [
                'titulo'     => '44º Congresso de Gestão Pública',
                'data_inicio'=> '2026-05-05 08:00:00',
                'data_fim'   => '2026-05-08 18:00:00',
                'local'      => 'Brasília - DF',
                'topicos'    => 'O que são Políticas Públicas; A importância da participação social; A divisão dos Poderes do Estado; Gestão Pública Municipal; Transparência e Controle Social; Planejamento e Orçamento Público; Lei de Responsabilidade Fiscal; Licitações e Contratos; Gestão de Pessoas no Setor Público; Ética e Integridade na Administração Pública.',
                'ativo'      => true,
                'ordem'      => 1,
            ],
            [
                'titulo'     => '31º Fórum de Gestão Pública',
                'data_inicio'=> '2026-05-12 08:00:00',
                'data_fim'   => '2026-05-15 18:00:00',
                'local'      => 'Brasília - DF',
                'topicos'    => 'Políticas Públicas e Desenvolvimento Regional; Cooperação entre Poderes Executivo e Legislativo; Participação Social e Controle Democrático; Modernização da Gestão Municipal; Desburocratização e Eficiência Administrativa.',
                'ativo'      => true,
                'ordem'      => 2,
            ],
            [
                'titulo'     => '33º Simpósio de Gestão Pública',
                'data_inicio'=> '2026-05-19 08:00:00',
                'data_fim'   => '2026-05-22 18:00:00',
                'local'      => 'Brasília - DF',
                'topicos'    => 'Transparência e Acesso à Informação; Lei de Responsabilidade Fiscal na Prática; Auditoria e Controle Interno; Gestão de Contratos e Licitações; Combate à Corrupção no Setor Público.',
                'ativo'      => true,
                'ordem'      => 3,
            ],
            [
                'titulo'     => '31º Seminário de Gestão Pública',
                'data_inicio'=> '2026-05-26 08:00:00',
                'data_fim'   => '2026-05-29 18:00:00',
                'local'      => 'Brasília - DF',
                'topicos'    => 'Capacitação de Agentes Políticos e Servidores Públicos; Vereadores, Prefeitos e Assessores: papel e responsabilidades; Câmaras Municipais: funcionamento e boas práticas; Orçamento Participativo; Indicadores de Gestão Municipal.',
                'ativo'      => true,
                'ordem'      => 4,
            ],
        ];

        foreach ($cursos as $c) {
            Curso::create($c);
        }

        // ── SiteConfig ──
        $configs = [
            'sobre_texto'          => 'A Escola de Gestão Pública Ulysses Guimarães é dedicada à promoção da capacitação de Agentes Políticos, Gestores Públicos e Servidores Públicos, com foco no crescimento regional por meio da transparência e cooperação entre os Poderes Executivo e Legislativo.',
            'publico_alvo'         => 'Servidores do Executivo e Legislativo, Prefeitos, Vice-Prefeitos, Vereadores e Assessores.',
            'endereco'             => 'Rua Q SDE Quadra 01 Conjunto E Lote, Nº04, Apt 102 Parte C, CEP 72.145-105, Brasília-DF',
            'telefone'             => '(61) 98654-5280',
            'whatsapp'             => '(61) 98654-5280',
            'email'                => 'contato@institutoulyssesguimaraes.com.br',
            'banner_titulo'        => 'Capacitação em Gestão Pública',
            'banner_subtitulo'     => 'Formação para Agentes Políticos, Gestores e Servidores Públicos em todo o Brasil.',
            'transparencia_texto'  => 'A Escola de Gestão Pública Ulysses Guimarães atua com total transparência, disponibilizando documentos e certificações para consulta pública.',
        ];

        foreach (