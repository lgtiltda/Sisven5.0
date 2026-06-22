# Referência de Interface — GestãoClick (modelo para o DopDin ERP)

> Documento de referência capturado da plataforma **GestãoClick** (gestaoclick.com) para
> guiar o redesenho do DopDin em um ERP multilojas completo.
> Capturado em: 21/06/2026.

---

## 1. Estrutura geral da tela (layout)

```
┌──────────────────────────────────────────────────────────────────────┐
│  [faixa topo] Sua versão de demonstração expira em 10 dias [Assine]    │  ← banner global
├──────────────────────────────────────────────────────────────────────┤
│ ☰  gestãoclick                         ▦  ✦  🔔   (LG avatar usuário)  │  ← topbar escura
├───────────┬──────────────────────────────────────────────────────────┤
│           │  Boa noite, LUCAS                              🏠 Início    │  ← saudação + breadcrumb
│  MENU     │ ┌──────────┐ ┌──────────┐ ┌───────────────────────────┐   │
│  LATERAL  │ │A receber │ │ A pagar  │ │ Recebimentos / Pagamentos │   │  ← cards KPI
│ (vertical)│ │  hoje    │ │  hoje    │ │   do mês (donut %)        │   │
│           │ │ R$ 0,00 ↗│ │ R$ 0,00 ↘│ │ Realizado/Falta/Previsto  │   │
│           │ └──────────┘ └──────────┘ └───────────────────────────┘   │
│           │ ┌─────────────────────┐ ┌─────────────────────────────┐   │
│           │ │ Fluxo de caixa      │ │ Gráfico de vendas           │   │  ← gráficos linha
│           │ │ (linha jan→jun)     │ │ (linha jan→jun)             │   │
│           │ └─────────────────────┘ └─────────────────────────────┘   │
│           │ ┌─────────────────────┐ ┌─────────────────────────────┐   │
│           │ │ Contas bancárias    │ │ Calendário (mês atual)      │   │
│           │ └─────────────────────┘ └─────────────────────────────┘   │
└───────────┴──────────────────────────────────────────────────────────┘
```

- **Topbar escura** fixa: botão sanduíche (recolhe menu), logo, e à direita ícones de
  apps (▦), novidades (✦), notificações (🔔) e avatar do usuário logado.
- **Menu lateral** vertical fixo à esquerda, com submenus expansíveis (caret `<`).
- **Área de conteúdo** com saudação contextual ("Boa noite, LUCAS") + breadcrumb "Início".

---

## 2. Menu lateral (estrutura completa)

Itens de 1º nível e seus submenus (os com `>` expandem):

| # | Item (ícone) | Submenu / Observação |
|---|--------------|----------------------|
| 1 | **Cadastros** ⌄ | Clientes · Fornecedores · Funcionários · Transportadoras · Opções auxiliares |
| 2 | **Itens** > | Produtos, serviços, categorias, marcas, unidades |
| 3 | **Orçamentos** > | Orçamentos / propostas |
| 4 | **Vendas** > | Pedidos de venda, PDV, devoluções |
| 5 | **Ordens de serviços** > | OS, agendamentos |
| 6 | **Estoque** > | Entradas, saídas, inventário, transferências entre lojas |
| 7 | **Financeiro** > | Contas a receber, contas a pagar, fluxo de caixa, contas bancárias |
| 8 | **Fiscal** > | NF-e / NFC-e, emissão e gestão fiscal |
| 9 | **Contratos** > | Contratos recorrentes |
| 10 | **Atendimentos** > | CRM / tickets |
| 11 | **Relatórios** > | Relatórios por módulo |
| 12 | **Configurações** > | Empresa/lojas, usuários, permissões, parâmetros |

> **Padrão de navegação:** menu agrupado por DOMÍNIO de negócio (não por tela solta).
> Cada grupo abre submenu. É o padrão que o DopDin deve adotar no redesenho.

---

## 3. Dashboard (componentes)

### 3.1 Cards de KPI (topo)
- **A receber hoje** — card verde, valor grande `R$ 0,00`, seta ↗, link "Ir para contas a receber".
- **A pagar hoje** — card vermelho, valor `R$ 0,00`, seta ↘, link "Ir para contas a pagar".
- **Recebimentos do mês / Pagamentos do mês** — card azul com 2 donuts de % e legenda
  **Realizado / Falta / Previsto**, link "Ir para fluxo de caixa".

### 3.2 Gráficos
- **Fluxo de caixa** — gráfico de linha, eixo X = meses (jan→jun 2026), tooltip "Saldo: 0,00".
- **Gráfico de vendas** — gráfico de linha, mesma escala temporal.

### 3.3 Widgets inferiores
- **Contas bancárias** — saldo por conta.
- **Calendário** — mês corrente (vencimentos / agenda).

> Todos os números do dashboard são **filtrados pela loja selecionada** (multiloja).

---

## 4. Mapeamento: o que o DopDin JÁ tem × o que falta

Menu atual do DopDin (`View/home.php`, via `alternarDivs(n)`):
Home(1) · Produtos(2) · Relatórios(3) · Usuários(4) · Contas(5) · Despesas(6) · Lojas(21).

| Domínio GestãoClick | DopDin hoje | Status / lacuna |
|---------------------|-------------|-----------------|
| Cadastros › Clientes | tabela `clientes` ✔ | OK (falta vínculo `cod_loja` consistente) |
| Cadastros › Fornecedores | tabela `fornecedores` ✔ | existe, sem tela dedicada robusta |
| Cadastros › Funcionários | tabela `usuarios` ✔ | OK |
| Cadastros › Transportadoras | ✖ | **FALTA** |
| Itens (Produtos) | `servicos` + `gestao_estoque` ✔ | OK (renomear conceito p/ "produtos") |
| Orçamentos | parcial (`pedidos`) | revisar fluxo |
| Vendas / PDV | `notas`, `pedidos`, carrinho ✔ | existe (carrinho de compras) |
| Ordens de serviço | ✖ | **FALTA** (se aplicável ao negócio) |
| Estoque (multi-loja) | `gestao_estoque.cod_loja` ✔ | falta transferência entre lojas |
| Financeiro › Receber | `financeiro_clientes`, `pag_par_pro` ✔ | falta tela "contas a receber" unificada |
| Financeiro › Pagar | `financeiro_empresa`, `debitos` ✔ | falta tela "contas a pagar" unificada |
| Financeiro › Caixa | `fechar_caixa` ✔ | OK |
| Fiscal (NF-e/NFC-e) | campos em `lojas` (série, ambiente) | **FALTA** emissão real |
| Contratos | ✖ | **FALTA** |
| Atendimentos/CRM | ✖ | **FALTA** |
| Configurações › Lojas | tabela `lojas` ✔ (2 lojas) | base existe — 1ª etapa do plano |
| Configurações › Permissões | `usuarios.permissao` | falta granularidade por loja |
| Dashboard com KPIs/gráficos | Home simples | **FALTA** dashboard estilo GestãoClick |

---

## 5. Plano de evolução acordado (ordem)

1. **Lojas e filiais** — cadastro/edição de lojas, matriz×filial, seleção de loja ativa.
2. **Produtos e estoque** — produtos multiloja, estoque por loja, transferências.
3. **Interfaces (revisão tela a tela)** — clientes, vendas, contas a receber/pagar por
   loja, e controle de permissão por loja.
4. (Transversal) **Dashboard** novo no padrão acima.

> Paleta/visual de referência: topbar escura, cards coloridos (verde=receber,
> vermelho=pagar, azul=resumo mensal), Bootstrap 5 + gráficos de linha/donut.
