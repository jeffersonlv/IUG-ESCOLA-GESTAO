<?php

namespace Database\Seeders;

use App\Models\Palestrante;
use Illuminate\Database\Seeder;

class PalestrantesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nome' => 'Anderson Gois',                               'descricao' => null,                                                    'foto' => 'anderson_gois.png'],
            ['nome' => 'Angélica Pieroni',                            'descricao' => 'Servidora TSE',                                         'foto' => 'angelica_pieroni.jpeg'],
            ['nome' => 'Carlos Rocha',                                'descricao' => null,                                                    'foto' => 'carlos_rocha.png'],
            ['nome' => 'Cleverson Alves dos Santos',                  'descricao' => null,                                                    'foto' => 'cleverson_alves_dos_santos.jpeg'],
            ['nome' => 'Cláudio Vieira de Oliveira',                  'descricao' => null,                                                    'foto' => 'claudio_vieira_de_oliveira.jpeg'],
            ['nome' => 'Dr. Daniel Carnacchioni',                     'descricao' => 'Juiz TJDFT',                                            'foto' => 'dr_daniel_carnacchioni.jpeg'],
            ['nome' => 'Dr. Enéias Rezende',                          'descricao' => 'Advogado',                                              'foto' => 'dr_eneias_rezende.png'],
            ['nome' => 'Dr. Fabio Ianni Goldfinger',                  'descricao' => 'Promotor de Justiça — MPMS',                           'foto' => 'dr_fabio_ianni_goldfinger.jpeg'],
            ['nome' => 'Dr. Fábio Esteves',                           'descricao' => 'Juiz de Direito do Distrito Federal e Territórios',    'foto' => 'dr_fabio_esteves.jpeg'],
            ['nome' => 'Dr. José Carlos Fernandes Júnior',            'descricao' => 'Promotor de Justiça — MPMG',                           'foto' => 'dr_jose_carlos_fernandes_junior.png'],
            ['nome' => 'Dr. Vitor Quintieri',                         'descricao' => 'Advogado Criminalista',                                'foto' => 'dr_vitor_quintieri.jpeg'],
            ['nome' => 'Dra. Clara Mota',                             'descricao' => 'Juíza Federal',                                        'foto' => 'dra_clara_mota.png'],
            ['nome' => 'Dra. Lilia Simone Rodrigues da Costa Vieira', 'descricao' => null,                                                    'foto' => 'dra_lilia_simone_rodrigues.jpeg'],
            ['nome' => 'Dra. Lívia Rabelo',                           'descricao' => 'Promotora de Justiça',                                 'foto' => 'dra_livia_rabelo.jpeg'],
            ['nome' => 'Dra. Renata Gil Alcantara Videira',           'descricao' => null,                                                    'foto' => 'dra_renata_gil_alcantara_videira.png'],
            ['nome' => 'Dra. Sara Costa',                             'descricao' => 'Psicóloga e Terapeuta Comportamental',                 'foto' => 'dra_sara_costa.jpeg'],
            ['nome' => 'Gustavo Nardi',                               'descricao' => 'Advogado',                                              'foto' => 'gustavo_nardi.png'],
            ['nome' => 'Jovanildo Ferreira Lima',                     'descricao' => 'Psicólogo',                                            'foto' => 'jovanildo_ferreira_lima.jpeg'],
            ['nome' => 'Lucas Grassi',                                'descricao' => null,                                                    'foto' => 'lucas_grassi.jpeg'],
            ['nome' => 'Marcelo Aro',                                 'descricao' => 'Secretário Chefe da Casa Civil de Minas Gerais',       'foto' => 'marcelo_aro.png'],
            ['nome' => 'Marcel Bernadi Marques',                      'descricao' => null,                                                    'foto' => 'marcel_bernadi_marques.jpeg'],
            ['nome' => 'Marcos Paulo de Oliveira',                    'descricao' => null,                                                    'foto' => 'marcos_paulo_de_oliveira.png'],
            ['nome' => 'Orestes Lobo',                                'descricao' => 'Jornalista',                                            'foto' => 'orestes_lobo.jpeg'],
            ['nome' => 'Oséias Lopes',                                'descricao' => 'Pedagogo',                                              'foto' => 'oseias_lopes.png'],
            ['nome' => 'Perla Roriz',                                 'descricao' => 'Advogada',                                              'foto' => 'perla_roriz.png'],
            ['nome' => 'Professor Alessandro Alencastro',             'descricao' => null,                                                    'foto' => 'prof_alessandro_alencastro.jpeg'],
            ['nome' => 'Professor Fernando Alencastro',               'descricao' => null,                                                    'foto' => 'prof_fernando_alencastro.jpeg'],
            ['nome' => 'Professor Fábio Costa',                       'descricao' => 'Policial Federal',                                     'foto' => 'prof_fabio_costa.jpeg'],
            ['nome' => 'Raul Belém',                                  'descricao' => 'Deputado Estadual',                                    'foto' => 'raul_belem.jpeg'],
            ['nome' => 'Ronaldo Vieira Francisco',                    'descricao' => null,                                                    'foto' => 'ronaldo_vieira_francisco.jpeg'],
            ['nome' => 'Salomão Ferreti',                             'descricao' => null,                                                    'foto' => 'salomao_ferreti.jpeg'],
            ['nome' => 'Taíse Ferreira',                              'descricao' => 'Jornalista',                                            'foto' => 'taise_ferreira.png'],
            ['nome' => 'Tiago Link',                                  'descricao' => null,                                                    'foto' => 'tiago_link.jpeg'],
        ];

        foreach ($data as $row) {
            Palestrante::firstOrCreate(
                ['nome' => $row['nome']],
                ['descricao' => $row['descricao'], 'foto' => $row['foto'], 'ativo' => true]
            );
        }
    }
}
