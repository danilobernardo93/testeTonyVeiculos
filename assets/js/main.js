window.onload = buscaVeiculos(1);

function dadosVeiculo(id) {
    document.getElementById("vendido").checked = false
    $("#btnEditar").show()
    var link = 'http://localhost/tony-veiculos/api'
    $.ajax({
        type: "get",
        url: link,
        data: {
            id: id,
            metodo: 'getVeiculos'
        },
        dataType: 'json',
        success: function (res) {
            dados = res.dados
            $("#montadora h6").text(dados[0].marca)
            $("#ano h6").text(dados[0].ano)
            $("#modelo").text(dados[0].veiculo)
            $("#descricao").text(dados[0].descricao)
            $("#btnDeletar").show()
            $("#marca2").val(dados[0].marca)
            $("#ano2").val(dados[0].ano)
            $("#modelo2").val(dados[0].veiculo)
            $("#descricao2").text(dados[0].descricao)
            $("#veiculoID").val(dados[0].id)

            if (dados[0].vendido == 1) {
                document.getElementById("vendido").checked = true
            }
        }
    })
}

function buscaVeiculos(pagina = 1) {
    var veiculo = $("#buscaVeiculo").val();
    var link = 'http://localhost/tony-veiculos/api/'
    var modelo = $("#valorTipoBusca").val()
    
    arrayData = {metodo: 'getVeiculos',veiculo: veiculo,pagina: pagina,marca: modelo}

    if($("#tipoBusca").val() == 'veiculo')
    {
        arrayData = {metodo: 'getVeiculos',veiculo: veiculo,pagina: pagina,veiculo: modelo}
    }

    $.ajax({
        type: "get",
        url: link,
        data: arrayData,
        dataType: 'json',
        success: function (res) {
            res.status == 'sucesso' ? montaListaVeiculo(res.dados) : nenhumVeiculoEncontrado();
            $(".pagination li").remove();
            $(".pagination").append(res.paginas);
            $("#valorTipoBusca option").remove();
            $("#valorTipoBusca").append(res.marcasModelos);
        }
    })

    
}

function montaListaVeiculo(dados) {
    var numRegistros = dados.length
    var lista = '';
    for (i = 0; i < numRegistros; i++) {
        lista += '<tr>\
                <td>\
                    <div class="row">\
                        <div class="col-9">\
                            <div class="montadora">'+ dados[i].marca + '</div>\
                            <div class="modelo">'+ dados[i].veiculo + '</div>\
                            <div class="ano">'+ dados[i].ano + '</div>\
                        </div>\
                        <div class="col-3">\
                            <i class="fa fa-pencil-square-o fa-2x mt-4 float-right" aria-hidden="true"  onclick="dadosVeiculo('+ dados[i].id + ')"></i>\
                        </div>\
                    </div>\
                </td>\
            </tr>';
    }
    $("#tabelaVeiculos tbody tr").remove();
    $("#tabelaVeiculos tbody").append(lista);
}

function nenhumVeiculoEncontrado() {
    lista = '<tr>\
            <td>\
                <div class="row">\
                    <div class="col-12">\
                        Nenhum veículo encontrado\
                    </div>\
                </div>\
            </td>\
        </tr>';
    $("#tabelaVeiculos tr").remove();
    $("#tabelaVeiculos").append(lista);
}

function adicionarVeiculo() {
    var marca = $("#marca2").val()
    var ano = $("#ano2").val()
    var veiculo = $("#modelo2").val()
    var descricao = $("#descricao2").val()
    var id = $("#veiculoID").val()
    var vendido = $("#vendido").prop("checked") ? 1 : 0;


    var link = 'http://localhost/tony-veiculos/api/'
    $.ajax({
        type: 'post',
        url: link,
        data: {
            id: id,
            ano: ano,
            marca: marca,
            veiculo: veiculo,
            vendido: vendido,
            descricao: descricao,
            metodo: 'adicionarVeiculo'
        },
        dataType: 'json',
        success: function (res) {
            console.log(res)
            alert(res.dados)
            location.reload()
        }
    })

}

function editaVeiculo() {
    var marca = $("#marca2").val()
    var ano = $("#ano2").val()
    var veiculo = $("#modelo2").val()
    var descricao = $("#descricao2").val()
    var id = $("#veiculoID").val()
    var vendido = $("#vendido").prop("checked") ? 1 : 0;


    var link = 'http://localhost/tony-veiculos/api/curl.php'
    $.ajax({
        type: 'post',
        url: link,
        data: {
            id: id,
            ano: ano,
            marca: marca,
            veiculo: veiculo,
            vendido: vendido,
            descricao: descricao,
            requisicao: 'PUT',
            metodo: 'atualizaVeiculo'
        },
        dataType: 'json',
        success: function (res) {
            dados = JSON.parse(res)

            alert(dados.dados)
            location.reload()
        }
    })

}

function deletarVeiculo() {
    document.getElementById("vendido").checked = false
    var id = $("#veiculoID").val()
    var link = 'http://localhost/tony-veiculos/api/curl.php'

    if (confirm("Deseja realmente deletar o veículo ?")) {
        $.ajax({
            type: "post",
            url: link,
            data: {
                id: id,
                requisicao: 'DELETE'
            },
            dataType: 'json',
            success: function (res) {
                dados = JSON.parse(res)
                console.log(dados.dados)
                alert(dados.dados)
                location.reload()
            }
        })
    }
}

function salvar() {
    var id = $("#veiculoID").val()
    id != '' ? editaVeiculo() : adicionarVeiculo()
}

function abrirModal() {
    $("#btnDeletar").hide()
    $("#marca2").val('')
    $("#ano2").val('')
    $("#modelo2").val('')
    $("#descricao2").val('')
    $("#veiculoID").val('')
    $("#vendido").prop("checked", false);
    $("#modalEditarVeiculo").modal();
}

function buscaVeiculosPor()
{
    var tipoBusca = $("#tipoBusca").val();
    $("#valorTipoBusca").val("")
    $(".oculta").css('display','none');
    $(".tipo-"+tipoBusca).css('display','block');

}