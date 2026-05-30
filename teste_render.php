$dados = [
    "titulo" => "33º Seminário de Gestão Pública",
    "data_inicio" => "2026-07-14",
    "data_fim" => "2026-07-17",
    "local" => "Brasília - DF",
    "investimento" => "R$1.490,00 por participante",
    "carga_horaria" => "10h/aulas",
    "publico_alvo" => "Vereadores, Assessores, Prefeitos, Vice-Prefeitos e Servidores do Executivo e Legislativo",
    "programacao" => [
        ["dia_semana"=>"Terça-feira","data"=>"14/07","horario"=>"14:00 às 19:00","tipo"=>"credenciamento","temas"=>["Credenciamento e entrega de materiais."]],
        ["dia_semana"=>"Quarta-feira","data"=>"15/07","horario"=>"08:00 às 12:00","tipo"=>"palestra","temas"=>["O sistema de Controle Interno.","O Controle efetivo dos bens públicos.","Padronização e normatização dos bens públicos."]],
        ["dia_semana"=>"Quinta-feira","data"=>"16/07","horario"=>"08:00 às 12:00","tipo"=>"palestra","temas"=>["A Gestão Fiscal responsável.","Limites da Lei de responsabilidade fiscal.","A Fiscalização na elaboração de convênios."]],
        ["dia_semana"=>"Sexta-feira","data"=>"17/07","horario"=>"07:00 às 09:00","tipo"=>"encerramento","temas"=>["Produtividade e inteligência emocional.","A valorização do Servidor.","Encerramento e entrega de certificados."]],
    ],
    "folder_palestrantes" => [
        ["nome"=>"Dr. Enéias Rezende","cargo"=>"Advogado"],
        ["nome"=>"Dr. Fábio F. Esteves","cargo"=>"Juiz de Direito do Distrito Federal e Territórios"],
        ["nome"=>"Dr. Daniel Carnacchioni","cargo"=>"Juiz TJDFT"],
        ["nome"=>"Fernando Alencastro","cargo"=>"Professor"],
        ["nome"=>"Angélica Pieroni","cargo"=>"Servidora Federal"],
    ],
];
$configs = ["whatsapp"=>"(61) 98654-5280","email"=>"contato@institutoulyssesguimaraes.com.br"];
$bgPath = public_path("images/background_folder.jpg");
$bgBase64 = file_exists($bgPath) ? "data:image/jpeg;base64," . base64_encode(file_get_contents($bgPath)) : "";
$logoBase64 = "";
$html = view("admin.cursos.folder_pdf", compact("dados","configs","logoBase64","bgBase64"))->render();
$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper("a4","portrait");
file_put_contents(public_path("teste_folder.pdf"), $pdf->output());
echo "OK " . strlen($html) . " bytes html";
