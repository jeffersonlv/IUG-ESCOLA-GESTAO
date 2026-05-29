<?php

namespace Database\Seeders;

use App\Models\Palestrante;
use Illuminate\Database\Seeder;

class PalestrantesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nome' => 'Anderson Gois',                               'descricao' => null,                                                    'foto' => '/images/palestrantes/anderson_gois.png'],
            ['nome' => 'Angélica Pieroni',                            'descricao' => 'Servidora TSE',                                         'foto' => '/images/palestrantes/angelica_pieroni.jpeg'],
            ['nome' => 'Carlos Rocha',                                'descricao' => null,                                                    'foto' => '/images/palestrantes/carlos_rocha.png'],
            ['nome' => 'Cleverson Alves dos Santos',                  'descricao' => null,                                                    'foto' => '/images/palestrantes/cleverson_alves_dos_santos.jpeg'],
            ['nome' => 'Cláudio Vieira de Oliveira',                  'descricao' => null,                                                    'foto' => '/images/palestrantes/claudio_vieira_de_oliveira.jpeg'],
            ['nome' => 'Dr. Daniel Carnacchioni',                     'descricao' => 'Juiz TJDFT',                                            'foto' => '/images/palestrantes/dr_daniel_carnacchioni.jpeg'],
            ['nome' => 'Dr. Enéias Rezende',                          'descricao' => 'Advogado',                                              'foto' => '/images/palestrantes/dr_eneias_rezende.png'],
            ['nome' => 'Dr. Fabio Ianni Goldfinger',                  'descricao' => 'Promotor de Justiça — MPMS',                            'foto' => '/images/palestrantes/dr_fabio_ianni_goldfinger.jpeg'],
            ['nome' => 'Dr. Fábio Esteves',                           'descricao' => 'Juiz de Direito do Distrito Federal e Territórios',     'foto' => '/images/palestrantes/dr_fabio_esteves.jpeg'],
            ['nome' => 'Dr. José Carlos Fernandes Júnior',            'descricao' => 'Promotor de Justiça — MPMG',                            'foto' => '/images/palestrantes/dr_jose_carlos_fernandes_junior.png'],
            ['nome' => 'Dr. Vitor Quintieri',                         'descricao' => 'Advogado Criminalista',                                 'foto' => '/images/palestrantes/dr_vitor_quintieri.jpeg'],
            ['nome' => 'Dra. Clara Mota',                             'descricao' => 'Juíza Federal',                                         'foto' => '/images/palestrantes/dra_clara_mota.png'],
            ['nome' => 'Dra. Lilia Simone Rodrigues da Costa Vieira', 'descricao' => null,                                                    'foto' => '/images/palestrantes/dra_lilia_simone_rodrigues.jpeg'],
            ['nome' => 'Dra. Lívia Rabelo',                           'descricao' => 'Promotora de Justiça',                                  'foto' => '/images/palestrantes/dra_livia_rabelo.jpeg'],
            ['nome' => 'Dra. Renata Gil Alcantara Videira',           'descricao' => null,                                                    'foto' => '/images/palestrantes/dra_renata_gil_alcantara_videira.png'],
            ['nome' => 'Dra. Sara Costa',                             'descricao' => 'Psicóloga e Terapeuta Comportamental',                  'foto' => '/images/palestrantes/dra_sara_costa.jpeg'],
            ['nome' => 'Gustavo Nardi',                               'descricao' => 'Advogado',                                              'foto' => '/images/palestrantes/gustavo_nardi.png'],
            ['nome' => 'Jovanildo Ferreira Lima',                     'descricao' => 'Psicólogo',                                             'foto' => '/images/palestrantes/jovanildo_ferreira_lima.jpeg'],
            ['nome' => 'Lucas Grassi',                                'descricao' => null,                                                    'foto' => '/images/palestrantes/lucas_grassi.jpeg'],
            ['nome' => 'Marcelo Aro',                                 'descricao' => 'Secretário Chefe da Casa Civil de Minas Gerais',        'foto' => '/images/palestrantes/marcelo_aro.png'],
            ['nome' => 'Marcel Bernadi Marques',                      'descricao' => null,                                                    'foto' => '/images/palestrantes/marcel_bernadi_marques.jpeg'],
            ['nome' => 'Marcos Paulo de Oliveira',                    'descricao' => null,                                                    'foto' => '/images/palestrantes/marcos_paulo_de_oliveira.png'],
            ['nome' => 'Orestes Lobo',                                'descricao' => 'Jornalista',                                            'foto' => '/images/palestrantes/orestes_lobo.jpeg'],
            ['nome' => 'Oséias Lopes',                                'descricao' => 'Pedagogo',                                              'foto' => '/images/palestrantes/oseias_lopes.png'],
            ['nome' => 'Perla Roriz',                                 'descricao' => 'Advogada',                                              'foto' => '/images/palestrantes/perla_roriz.png'],
            ['nome' => 'Professor Alessandro Alencastro',             'descricao' => null,                                                    'foto' => '/images/palestrantes/prof_alessandro_alencastro.jpeg'],
            ['nome' => 'Professor Fernando Alencastro',               'descricao' => null,                                                    'foto' => '/images/palestrantes/prof_fernando_alencastro.jpeg'],
            ['nome' => 'Professor Fábio Costa',                       'descricao' => 'Policial Federal',                                      'foto' => '/images/palestrantes/prof_fabio_costa.jpeg'],
            ['nome' => 'Raul Belém',                                  'descricao' => 'Deputado Estadual',                                     'foto' => '/images/palestrantes/raul_belem.jpeg'],
            ['nome' => 'Ronaldo Vieira Francisco',                    'descricao' => null,                                                    'foto' => '/images/palestrantes/ronaldo_vieira_francisco.jpeg'],
            ['nome' => 'Salomão Ferreti',                             'descricao' => null,                                                    'foto' => '/images/palestrantes/salomao_ferreti.jpeg'],
            ['nome' => 'Taíse Ferreira',                              'descricao' => 'Jornalista',                                            'foto' => '/images/palestrantes/taise_ferreira.png'],
            ['nome' => 'Tiago Link',                                  'descricao' => null,                                                    'foto' => '/images/palestrantes/tiago_link.jpeg'],
        ];

        foreach ($data as $row) {
            Palestrante::firstOrCreate(
                ['nome' => $row['nome']],
                ['descricao' => $row['descricao'], 'foto' => $row['foto'], 'ativo' => true]
            );
        }
    }
}
