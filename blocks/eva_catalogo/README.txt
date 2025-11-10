# Bloco EVA Catálogo de Cursos

Este bloco exibe um catálogo de cursos no estilo da Escola Virtual do Governo (EVG), com filtros dinâmicos, busca, AJAX e integração com carga horária média por aluno.

---

## ✅ Funcionalidades

- Exibição de cursos com cards
- Imagem, nome, categoria, carga horária média
- Botão "Saiba mais" para a página do curso
- Filtros dinâmicos:
  - Carga horária (até 20h, 21–40h, etc.)
  - Área temática
  - Tipo de oferta (Curso, Trilha, Série)
  - Instituição ofertante
- Busca por nome do curso
- Compatível com Moodle 3.11.8 até 5.0+

---

## 🧩 Instalação

1. Extraia `block_eva_catalogo_final.zip`
2. Copie a pasta `eva_catalogo` para dentro de `blocks/` no diretório do seu Moodle
3. Acesse o Moodle como administrador e vá para:
   - **Administração do site → Notificações**
   - Siga o processo de instalação do plugin

---

## ⚙️ Configuração adicional

### Criar campo personalizado:

1. Vá para:
   - Administração do site → Cursos → Campos personalizados de curso
2. Adicione um novo campo:
   - Tipo: Caixa de seleção
   - Nome: Exibir no Catálogo
   - Shortname: `catalogo`
   - Valor padrão: Não marcado
3. Marque este campo nos cursos que você deseja exibir no catálogo.

---

## 🧠 Requisitos para o curso aparecer:

- Curso deve estar **visível**
- Ter pelo menos 1 registro em `mdl_eva_course_workload`
- Ter o campo personalizado `catalogo = 1`

---

## 📤 Atualização

Para atualizar o bloco:
1. Substitua os arquivos dentro da pasta `blocks/eva_catalogo`
2. Vá em **Administração do site → Notificações**

---

## 🧹 Desinstalação

- Remova a pasta `blocks/eva_catalogo`
- Vá em **Administração do site → Plugins → Blocos → Gerenciar blocos**
- Clique em **Desinstalar** ao lado de *Catálogo de Cursos EVA*

---

Desenvolvido com ❤️ para uso interno.
