<?php

namespace App\Http\Controllers;

use App\AdvogadoParcipanteProcesso;
use App\Advogados;
use App\ClienteProcesso;
use App\Clientes;
use App\ContrarioProcesso;
use App\Deposito;
use App\DepositoJudicial;
use App\DepositoJudicialProcesso;
use App\DepositoProcesso;
use App\ParticipanteProcesso;
use App\Pericia;
use App\Processos;
use App\PericiaProcesso;
use App\Recolhimento;
use App\RecolhimentoProcesso;
use App\Tribunal;
use App\Contrario;
use App\Vara;
use App\Pedidos;
use App\PedidoProcesso;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

class ProcessController extends Controller
{
    //


    public function listar()
    {
        $process = Processos::all();


        return view('process.list', ['processes' => $process ]);

    }


    public function criar()
    {

        $clientes                   = Clientes::all()->toArray();
        $tribunais                  = Tribunal::all()->toArray();
        $varas                      = Vara::all()->toArray();
        $advogados                  = Advogados::where('tipo', 1)->get()->toArray();
        $advogados_contrario        = Advogados::where('tipo', 2)->get()->toArray();
        $advogados_participantes    = Advogados::where('tipo', 3)->get()->toArray();
        $contrarios                 = Contrario::all()->toArray();
        $pericias                   = Pericia::all()->toArray();
        $depositos                  = Deposito::all()->toArray();
        $depositos_judiciais        = DepositoJudicial::all()->toArray();
        $recolhimentos              = Recolhimento::all()->toArray();

        $pedidos                    = Pedidos::all()->toArray();


        return view('process.create',[
            "clientes"                  => $clientes ,
            "tribunais"                 => $tribunais ,
            "varas"                     => $varas,
            "advogados"                 => $advogados,
            "advogados_contrario"       => $advogados_contrario,
            "advogados_participantes"   => $advogados_participantes,
            "contrarios"                => $contrarios,
            "pericias"                  => $pericias,
            "depositos"                 => $depositos,
            "depositos_judiciais"       => $depositos_judiciais,
            "recolhimentos"             => $recolhimentos,
            "pedidos"                   => $pedidos,
        ]);


    }

    public function save(Request $request)
    {

        $number                 = $request->input('number');
        $polo                   = $request->input('polo');
        $value                  = $request->input('valor');
        $audiencia              = $request->input('audiencia');
        $adv_responsavel        = $request->input('adv_responsavel');
        $adv_terceiro           = $request->input('adv_terceiro');
        $ocorrencia_inaugural   = $request->input('ocorrencia_inaugural');

        $processo_id = $request->input('processo_id');



        if ($request->input('data_ajuizamento')) {

            $d_ajuizamento = \DateTime::createFromFormat("d/m/Y", date('d/m/Y',strtotime($request->input('data_ajuizamento'))))->format("m/d/Y");

            $data_ajuizamento = date("Y-m-d", strtotime($d_ajuizamento));
        } else {
            echo 'error';
        }

        if ($request->input('data_audiencia_inaugural')) {
            $d_inaugural = \DateTime::createFromFormat("d/m/Y H:i",  date('d/m/Y H:i',strtotime($request->input('data_audiencia_inaugural'))))->format("m/d/Y H:i");
            $data_audiencia_inaugural = date("Y-m-d H:i", strtotime($d_inaugural));
            $type_audiencia     = $request->input('type_audiencia');
        }
        else{
            $type_audiencia     =  NULL;
        }

        $tipo_processo      = $request->input('tipo');

        $deposito_judicial  = $request->input('deposito_judicial');

        $pericias           = $request->input('pericias');             // Se teve perícia
        $pericia            = $request->input('pericia_natureza');     // Motivo da perícia
        $value_pericia      = $request->input('pericia_honorario');    // Valor da perícia

        $depositos          = $request->input('depositos');            // Se teve deposito
        $deposito           = $request->input('deposito_motivo');      // Motivo da deposito
        $value_deposito     = $request->input('deposito_valor');       // Valor da deposito

        $depositos_judiciais    = $request->input('depositos_judiciais');            // Se teve deposito
        $deposito_jud_mot       = $request->input('deposito_judicial_motivo');      // Motivo da deposito
        $deposito_jud_val       = $request->input('deposito_judicial_valor');       // Valor da deposito

        $recolhimentos      = $request->input('recolhimentos');        // Se teve recolhimento
        $recolhimento       = $request->input('recolhimento_motivo');  // Motivo da recolhimento
        $value_recolhimento = $request->input('recolhimento_valor');   // Valor da recolhimento


        $clientes           = $request->input('cliente_id');
        $participantes      = $request->input('participante_name');
        $adv_participantes  = $request->input('adv_participante_id');
        $contrarios         = $request->input('contrario_id');



        if( isset( $processo_id  )){

            $processo = Processos::find($processo_id);

        }else
        {
            // Criação do Processo
            $processo = new Processos();


        }
        $processo->numero_processual    = $number;
        $processo->polo                 = $polo;
        $processo->type                 = $tipo_processo;
        $processo->valor_causa          = $value;
        $processo->data_ajuizamento     = $data_ajuizamento;
        $processo->inaugural            = $audiencia;
        $processo->pericia              = $pericias;
        $processo->adv_owner            = $adv_responsavel;
        $processo->adv_third_party      = $adv_terceiro;
        $processo->ocorrencia_inaugural = $ocorrencia_inaugural;
        $processo->deposito_judicial    = $deposito_judicial;
        $processo->type_audiencia       = $type_audiencia;

        if (isset($data_audiencia_inaugural))
            $processo->data_audiencia_inaugural = $data_audiencia_inaugural;

        if( isset( $processo_id  )) {
            $processo->update();
        }else
        {

            $processo->save();
        }


        if( isset( $processo_id  )) {
            ClienteProcesso::where('processo_id', $processo_id)->delete();
        }

        foreach ($clientes as $key => $client) {
            $clienteProcesso                = new ClienteProcesso();
            $clienteProcesso->cliente_id    = $client;
            $clienteProcesso->processo_id   = $processo->id;
            $clienteProcesso->save();

        }

        if( isset( $processo_id  )) {
            ParticipanteProcesso::where('processo_id', $processo_id)->delete();
        }
        if (isset($participantes))
        {
            foreach($participantes as $key=>$participante)
            {
                $participanteProcesso                = new ParticipanteProcesso();
                $participanteProcesso->participante  = $participante;
                $participanteProcesso->processo_id   = $processo->id;
                $participanteProcesso->save();
            }
        }

        if( isset( $processo_id  )) {
            AdvogadoParcipanteProcesso::where('processo_id', $processo_id)->delete();
        }
        if (isset($adv_participantes))
        {
            foreach($adv_participantes as $key=>$adv)
            {
                $advParticipanteProcesso                = new AdvogadoParcipanteProcesso();
                $advParticipanteProcesso->advogado_id   = $adv;
                $advParticipanteProcesso->processo_id   = $processo->id;
                $advParticipanteProcesso->save();
            }
        }

        if( isset( $processo_id  )) {
            ContrarioProcesso::where('processo_id', $processo_id)->delete();
        }
        if (isset($contrarios))
        {
            foreach($contrarios as $key=>$contrario)
            {
                $contrarioProcesso                = new ContrarioProcesso();
                $contrarioProcesso->contrario_id  = $contrario;
                $contrarioProcesso->processo_id   = $processo->id;
                $contrarioProcesso->save();
            }
        }

        // Criação das Perícias do Processo
        if( isset( $processo_id  )) {
            PericiaProcesso::where('processo_id', $processo_id)->delete();
        }
        if($pericias == 1 && isset($pericia))
        {
            foreach($pericia as $key=>$type_pericia)
            {
                $pericia_processo                       = new PericiaProcesso();
                $pericia_processo->processo_id          = $processo->id;
                $pericia_processo->pericia_id           = $type_pericia;
                $pericia_processo->pericias_honorarios  = $value_pericia[$key];
                $pericia_processo->save();
            }
        }

        // Criação dos Depositos do Processo
        if( isset( $processo_id  )) {
            DepositoProcesso::where('processo_id', $processo_id)->delete();
        }
        if($depositos == 1 && isset($deposito))
        {
            foreach($deposito as $key=>$type_deposito)
            {
                $pedido_processo                    = new DepositoProcesso();
                $pedido_processo->processo_id       = $processo->id;
                $pedido_processo->deposito_id       = $type_deposito;
                $pedido_processo->deposito_valor    = $value_deposito[$key];
                $pedido_processo->save();
            }
        }

        // Criação dos Deposito Judicial do Processo
        if( isset( $processo_id  )) {
            DepositoJudicialProcesso::where('processo_id', $processo_id)->delete();
        }
        if($depositos_judiciais == 1 && isset($deposito_jud_mot))
        {
            foreach($deposito_jud_mot as $key=>$type_deposito)
            {
                $dep_jud_proc                           = new DepositoJudicialProcesso();
                $dep_jud_proc->processo_id              = $processo->id;
                $dep_jud_proc->deposito_judicial_id     = $type_deposito;
                $dep_jud_proc->deposito_valor           = $deposito_jud_val[$key];
                $dep_jud_proc->save();
            }
        }

        // Criação dos Recolhimentos do Processo
        if( isset( $processo_id  )) {
            RecolhimentoProcesso::where('processo_id', $processo_id)->delete();
        }
        if($recolhimentos == 1 && isset($recolhimento))
        {
            foreach($recolhimento as $key=>$type_recolhimento)
            {
                $recolhimento_processo                      = new RecolhimentoProcesso();
                $recolhimento_processo->processo_id         = $processo->id;
                $recolhimento_processo->recolhimento_id     = $type_recolhimento;
                $recolhimento_processo->recolhimento_valor  = $value_recolhimento[$key];
                $recolhimento_processo->save();
            }
        }

        $pedido_motivo      = $request->input('pedido_motivo');        // Pedido
        $valor_pedido       = $request->input('pedido_valor');         // Valor do pedido
        $risco_pedido       = $request->input('pedido_risco');         // Risco do pedido

        // Criação dos Peridos do Processo
        if( isset( $processo_id  )) {
            PedidoProcesso::where('processo_id', $processo_id)->delete();
        }
        if(isset($pedido_motivo)){

            foreach($pedido_motivo as $key=>$type_pedido)
            {
                $pedido_processo                = new PedidoProcesso();
                $pedido_processo->processo_id   = $processo->id;
                $pedido_processo->pedido_id     = $type_pedido;
                $pedido_processo->pedido_valor  = $valor_pedido[$key];
                $pedido_processo->risco         = $risco_pedido[$key];
                $pedido_processo->save();
            }
        }

        return redirect()->route('processos.listar');

    }


    public function editar($id)
    {
        $process = Processos::find($id);

        if($process instanceof Processos)
        {
            $pivot                      = ClienteProcesso::where('processo_id', $id)->get();

            $clientes_selected = [];
            foreach($pivot as $p)
            {
                $clientes_selected[]    = $p->cliente()->get()[0]->toArray();
            }

            $pivot                      = ParticipanteProcesso::where('processo_id', $id)->get();

            $parts = [];
            foreach($pivot as $p)
            {
                $parts[]                = $p->participante;
            }


            $pivot                      = AdvogadoParcipanteProcesso::where('processo_id', $id)->get();

            $advs_part_selected = [];
            foreach($pivot as $p)
            {
                $advs_part_selected[]   = $p->advogado()->get()[0]->toArray();
            }

            $pivot                      = ContrarioProcesso::where('processo_id', $id)->get();

            $conts_selected = [];
            foreach($pivot as $p)
            {
                $conts_selected[]       = $p->contrario()->get()[0]->toArray();
            }

            $pivot                      = PedidoProcesso::where('processo_id', $id)->get();

            $pedidos_selected = [];
            foreach($pivot as $p)
            {
                $pedidos_selected[]     =
                    array(
                        'pedido_processo' => $p->toArray(),
                        'type' => $p->pedido()->get()[0]->toArray()['type']
                    );
            }

            $pivot                      = RecolhimentoProcesso::where('processo_id', $id)->get();

            $recolhimentos_selected  = [];
            $hasRecolhimento        = false;

            foreach($pivot as $p)
            {
                $hasRecolhimento = true;
                $recolhimentos_selected[]     =
                    array(
                        'recolhimento_processo' => $p->toArray(),
                        'type' => $p->recolhimento()->get()[0]->toArray()['type']
                    );
            }

            $pivot                      = DepositoJudicialProcesso::where('processo_id', $id)->get();

            $depositos_judiciais_selected   = [];
            $hasDepositoJudicial            = false;

            foreach($pivot as $p)
            {
                $hasDepositoJudicial = true;
                $depositos_judiciais_selected[]     =
                    array(
                        'deposito_judicial_processo' => $p->toArray(),
                        'type' => $p->deposito_judicial()->get()[0]->toArray()['type']
                    );
            }

            $pivot                      = DepositoProcesso::where('processo_id', $id)->get();

            $depositos_selected   = [];
            $hasDeposito          = false;

            foreach($pivot as $p)
            {
                $hasDeposito = true;
                $depositos_selected[]     =
                    array(
                        'deposito_processo' => $p->toArray(),
                        'type' => $p->deposito()->get()[0]->toArray()['type']
                    );
            }
            $pivot                      = PericiaProcesso::where('processo_id', $id)->get();

            $pericias_selected   = [];
            $hasPericia          = false;

            foreach($pivot as $p)
            {
                $hasPericia = true;
                $pericias_selected[]     =
                    array(
                        'pericia_processo' => $p->toArray(),
                        'type' => $p->pericia()->get()[0]->toArray()['type']
                    );
            }

            $polo_passivo_selected      = false;
            $polo_ativo_selected        = false;
            switch($process->polo){

                case "ativo":
                    $polo_ativo_selected = [ 'checked' => 'checked' ];
                    break;
                case "passivo":
                    $polo_passivo_selected = [ 'checked' => 'checked' ];
                    break;

            }

            $hasAudiencia = false;
            if($process->inaugural == "sim")
                $hasAudiencia = true;

            $data_audiencia_selected = $process->data_audiencia_inaugural;
            $una_selected           = false;
            $inicial_selected       = false;
            switch($process->type_audiencia){

                case "Una":
                    $una_selected       = [ 'checked' => 'checked' ];
                    break;
                case "passivo":
                    $inicial_selected   = [ 'checked' => 'checked' ];
                    break;

            }


            $administrativo_selected    = false;
            $civel_selected             = false;
            $criminal_selected          = false;
            $trabalhista_selected       = false;
            $tributario_selected        = false;

            switch($process->type){

                // @todo - Mudar os cases para os nomes certos
                case "type1":
                    $administrativo_selected = [ 'checked' => 'checked' ];
                    break;
                case "type2":
                    $civel_selected = [ 'checked' => 'checked' ];
                    break;
                case "tipo3":
                    $criminal_selected = [ 'checked' => 'checked' ];
                    break;
                case "type4":
                    $trabalhista_selected = [ 'checked' => 'checked' ];
                    break;
                case "type5":
                    $tributario_selected = [ 'checked' => 'checked' ];
                    break;
            }

            $advogado_selected          = Advogados::find($process->adv_owner)->toArray();
            $number_selected            = $process->numero_processual;
            $advogado_contr_selected    = Advogados::find($process->adv_third_party)->toArray();

            $value_selected             = number_format($process->valor_causa,2,',','.');
            $date_ajuizamento_selected  = $process->data_ajuizamento;

            $ocorrencia_selected        = $process->ocorrencia_inaugural;

            $clientes                   = Clientes::all()->toArray();

            $tribunais                  = Tribunal::all()->toArray();
            $varas                      = Vara::all()->toArray();
            $advogados                  = Advogados::where('tipo', 1)->get()->toArray();
            $advogados_contrario        = Advogados::where('tipo', 2)->get()->toArray();
            $advogados_participantes    = Advogados::where('tipo', 3)->get()->toArray();
            $contrarios                 = Contrario::all()->toArray();
            $pericias                   = Pericia::all()->toArray();
            $depositos                  = Deposito::all()->toArray();
            $depositos_judiciais        = DepositoJudicial::all()->toArray();
            $recolhimentos              = Recolhimento::all()->toArray();

            $pedidos                    = Pedidos::all()->toArray();


            return view('process.edit',[

                "process"                   => $process,
                "clientes_selected"         => $clientes_selected ,
                "parts"                     => $parts ,
                "advs_part_selected"        => $advs_part_selected ,
                "conts_selected"            => $conts_selected ,
                "pedidos_selected"          => $pedidos_selected ,


                "polo_ativo_selected"       => $polo_ativo_selected ,
                "polo_passivo_selected"     => $polo_passivo_selected ,


                'administrativo_selected'   =>$administrativo_selected    ,
                'civel_selected'            =>$civel_selected             ,
                'criminal_selected'         =>$criminal_selected          ,
                'trabalhista_selected'      =>$trabalhista_selected       ,
                'tributario_selected'       =>$tributario_selected        ,

                'advogado_selected'         =>$advogado_selected          ,
                'number_selected'           =>$number_selected            ,
                'advogado_contr_selected'   =>$advogado_contr_selected    ,
                'value_selected'            =>$value_selected             ,
                'date_ajuizamento_selected' =>$date_ajuizamento_selected  ,

                'ocorrencia_selected'       =>$ocorrencia_selected      ,

                'hasRecolhimento'           =>$hasRecolhimento          ,
                'recolhimentos_selected'    =>$recolhimentos_selected   ,

                'hasDepositoJudicial'           =>$hasDepositoJudicial            ,
                'depositos_judiciais_selected'  =>$depositos_judiciais_selected   ,

                'hasAudiencia'                  =>$hasAudiencia             ,
                'data_audiencia_selected'       =>$data_audiencia_selected  ,
                'una_selected'                  =>$una_selected             ,
                'inicial_selected'              =>$inicial_selected         ,

                'hasPericia'                    =>$hasPericia               ,
                'pericias_selected'             =>$pericias_selected        ,


                'hasDeposito'           =>$hasDeposito          ,
                'depositos_selected'    =>$depositos_selected   ,


                "clientes"                  => $clientes ,
                "tribunais"                 => $tribunais ,
                "varas"                     => $varas,
                "advogados"                 => $advogados,
                "advogados_contrario"       => $advogados_contrario,
                "advogados_participantes"   => $advogados_participantes,
                "contrarios"                => $contrarios,
                "pericias"                  => $pericias,
                "depositos"                 => $depositos,
                "depositos_judiciais"       => $depositos_judiciais,
                "recolhimentos"             => $recolhimentos,
                "pedidos"                   => $pedidos,
            ]);
        }

        else
            return redirect()->route('processos.listar');

        //return view('process.edit', ['process' => $process ]);


    }

    public function deletar($id){

        $process = Processos::find($id);

        $process->delete();

        return redirect()->route('processos.listar');
    }

}
