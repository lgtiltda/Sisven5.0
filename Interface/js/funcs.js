var req;

function validacao(tipo, param, valor, id) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamente com o valor digitado no campo (método GET)
    var url = "Action/validacao.php?tipo=" + tipo + "&param=" + param + "&valor=" + valor + "&id=" + id;
    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Buscando Noticias..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoValidacao' + id).innerHTML = '';
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo busca.php
            var resposta = req.responseText;

            // Abaixo colocamos a(s) resposta(s) na div resultado
            document.getElementById('ResultadoValidacao' + id).innerHTML = resposta;
        }
    }



    req.send(null);


}

function validacaopagamento(tipo, param1, param2, param3, param4, param5, param6, id) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamente com o valor digitado no campo (método GET)
    var url = "Action/validacao.php?tipo=" + tipo + "&param1=" + param1 + "&param2=" + param2 + "&param3=" + param3 + "&param4=" + param4 + "&param5=" + param5 + "&param6=" + param6 + "&id=" + id;
    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Buscando Noticias..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoValidacao' + id).innerHTML = '';
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo busca.php
            var resposta = req.responseText;

            // Abaixo colocamos a(s) resposta(s) na div resultado
            document.getElementById('ResultadoValidacao' + id).innerHTML = resposta;
        }
    }



    req.send(null);


}



function validacaorelatorios(tipo, param1, param2, param3, param4, param5, param6, id) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamente com o valor digitado no campo (método GET)
    var url = "Action/validacao.php?tipo=" + tipo + "&param1=" + param1 + "&param2=" + param2 + "&param3=" + param3 + "&param4=" + param4 + "&param5=" + param5 + "&param6=" + param6 + "&id=" + id;
    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Buscando Noticias..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoValidacao' + id).innerHTML = '';
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo busca.php
            var resposta = req.responseText;

            // Abaixo colocamos a(s) resposta(s) na div resultado
            document.getElementById('ResultadoValidacao' + id).innerHTML = resposta;
        }
    }



    req.send(null);


}

function ControleCarrinhoPesquisarProduto(tipo, param1, param2, codnota) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamente com o valor digitado no campo (método GET)
    var url = "Action/retornarcarrinho.php?tipo=" + tipo + "&param1=" + param1 + "&param2=" + param2 + "&codnota=" + codnota;
    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Buscando Noticias..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('PesquisaProduto').innerHTML = '';

        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo busca.php
            var resposta = req.responseText;

            // Abaixo colocamos a(s) resposta(s) na div resultado
            document.getElementById('PesquisaProduto').innerHTML = resposta;
        }
    }

    req.send(null);
}
// FUNÇÃO PARA EXIBIR CADASTRAR PEDIDO
function CadastrarPedido(tipo, id, codnota, valor, categoria, qtd) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamento com a id da noticia (método GET)
    var url = "Action/retornarcarrinho.php?tipo=" + tipo + "&id=" + id + "&codnota=" + codnota + "&valor=" + valor + "&categoria=" + categoria + "&qtd=" + qtd;

    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Aguarde..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoValidacaoAddItem').innerHTML = "<div class='alert alert-danger'>Clique em F5 para atualizar os valores....</div>";
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo exibir.php
            var resposta = req.responseText;

            // Abaixo colocamos a resposta na div conteudo
            document.getElementById('ResultadoValidacaoAddItem').innerHTML = resposta;


            var minhaDiv = document.getElementById("ResultadoValidacao1");

            // Defina o conteúdo HTML da div
            minhaDiv.innerHTML = "Realize uma nova pesquisa para continuar comprando...";

            document.getElementById('txtPesquisarProd').focus();
            document.getElementById("txtPesquisarProd").value = "";

        }
    }


    req.send(null);



}
// FUNÇÃO PARA ATUALIZAR VALOR DO PEDIDO
function AtualizarValor(tipo, codnota) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamento com a id da noticia (método GET)
    var url = "Action/retornarcarrinho.php?tipo=" + tipo + "&codnota=" + codnota;

    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Aguarde..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoValidacaoAddItem').innerHTML = "<div class='alert alert-danger'>Clique em F5 para atualizar os valores....</div>";
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo exibir.php
            var resposta = req.responseText;

            // Abaixo colocamos a resposta na div conteudo
            document.getElementById('ResultadoValidacaoAddItem').innerHTML = resposta;


            var minhaDiv = document.getElementById("ResultadoValidacao1");

            // Defina o conteúdo HTML da div
            minhaDiv.innerHTML = "Realize uma nova pesquisa para continuar comprando...";

            document.getElementById('txtPesquisarProd').focus();
            document.getElementById("txtPesquisarProd").value = "";

        }
    }


    req.send(null);



}

//FUNÇÃO PARA DIVIDIR E APLICAR JUROS NO PAGAMENTO
function Parcelamento(tipo, valor, codnota, juros, juros2) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamente com o valor digitado no campo (método GET)
    var url = "Action/validacao.php?codnota=" + codnota + "&valor=" + valor + "&tipo=" + tipo + "&juros=" + juros + "&juros2=" + juros2;

    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Buscando Noticias..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('resultadodoparcelamento').innerHTML = 'Carregando...';
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo busca.php
            var resposta = req.responseText;

            // Abaixo colocamos a(s) resposta(s) na div resultado
            document.getElementById('resultadodoparcelamento').innerHTML = resposta;

        }
    }
    req.send(null);
}

//FUNCAO JAVASCRIP PARA PESQUISAR PRODUTOS E GERAR PAGINAS DE CONTROLE DE DADOS 
function PagPesquisarProdutos(tipo, param, valor) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamento com a id da noticia (método GET)
    var url = "Action/validacao.php?tipo=" + tipo + "&param=" + param + "&valor=" + valor;

    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Aguarde..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoPesquisaProduto').innerHTML = 'Aguarde...';
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo exibir.php
            var resposta = req.responseText;

            // Abaixo colocamos a resposta na div conteudo
            document.getElementById('ResultadoPesquisaProduto').innerHTML = resposta;
           
            alternarDivs(12);

        }
    }

    req.send(null);

}

//SALVAR DADOS DO PRODUTO
function EditarInformacoes(tipo, cod, nome, descricao, valor, categoria, est_max, est_mim, cod_barra) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamento com a id da noticia (método GET)
    var url = "Action/validacao.php?tipo=" + tipo + "&cod=" + cod + "&nome=" + nome + "&descricao=" + descricao + "&valor=" + valor + "&categoria=" + categoria + "&est_max=" + est_max + "&est_mim=" + est_mim + "&cod_barra=" + cod_barra;

    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Aguarde..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoPesquisaProduto').innerHTML = 'Aguarde...';
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo exibir.php
            var resposta = req.responseText;

            // Abaixo colocamos a resposta na div conteudo
            document.getElementById('ResultadoPesquisaProduto').innerHTML = resposta;

        }
    }

    req.send(null);

}

//FUNCAO JAVASCRIP PARA PESQUISAR PRODUTOS E GERAR PAGINAS DE CONTROLE DE DADOS 
function PagPesquisarUsuario(tipo, param, valor) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamento com a id da noticia (método GET)
    var url = "Action/validacao.php?tipo=" + tipo + "&param=" + param + "&valor=" + valor;

    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Aguarde..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoPesquisaUsuario').innerHTML = 'Aguarde...';
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo exibir.php
            var resposta = req.responseText;

            // Abaixo colocamos a resposta na div conteudo
            document.getElementById('ResultadoPesquisaUsuario').innerHTML = resposta;
           
            alternarDivs(14);

        }
    }

    req.send(null);

}

//SALVAR DADOS DO PRODUTO
function EditarInformacoesUsuario(tipo, cod, nome, descricao, valor, categoria, est_max, est_mim, cod_barra) {

    // Verificando Browser
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Arquivo PHP juntamento com a id da noticia (método GET)
    var url = "Action/validacao.php?tipo=" + tipo + "&cod=" + cod + "&nome=" + nome + "&descricao=" + descricao + "&valor=" + valor + "&categoria=" + categoria + "&est_max=" + est_max + "&est_mim=" + est_mim + "&cod_barra=" + cod_barra;

    // Chamada do método open para processar a requisição
    req.open("Get", url, true);

    // Quando o objeto recebe o retorno, chamamos a seguinte função;
    req.onreadystatechange = function () {

        // Exibe a mensagem "Aguarde..." enquanto carrega
        if (req.readyState == 1) {
            document.getElementById('ResultadoPesquisaUsuario').innerHTML = 'Aguarde...';
        }

        // Verifica se o Ajax realizou todas as operações corretamente
        if (req.readyState == 4 && req.status == 200) {

            // Resposta retornada pelo exibir.php
            var resposta = req.responseText;

            // Abaixo colocamos a resposta na div conteudo
            document.getElementById('ResultadoPesquisaUsuario').innerHTML = resposta;

        }
    }

    req.send(null);

}
//PAGINA VER NOTA COM TODAS INFORMAÇÕES
function PagVerTudo(tipo, codnota, id) {

    // Verificando Browser
        if (window.XMLHttpRequest) {
            req = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
    
    // Arquivo PHP juntamento com a id da noticia (método GET)
        var url = "Action/validacao.php?tipo=" + tipo +"&codnota=" + codnota +"&id=" + id;
    
    // Chamada do método open para processar a requisição
        req.open("Get", url, true);
    
    // Quando o objeto recebe o retorno, chamamos a seguinte função;
        req.onreadystatechange = function () {
    
            // Exibe a mensagem "Aguarde..." enquanto carrega
            if (req.readyState == 1) {
                document.getElementById('ResultadoValidacao'+id).innerHTML = 'Aguarde...';
            }
    
            // Verifica se o Ajax realizou todas as operações corretamente
            if (req.readyState == 4 && req.status == 200) {
    
                // Resposta retornada pelo exibir.php
                var resposta = req.responseText;
    
                // Abaixo colocamos a resposta na div conteudo
                document.getElementById('ResultadoValidacao'+id).innerHTML = resposta;
    
    
    
            }
        }
        req.send(null);
    
    }
    