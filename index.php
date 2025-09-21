<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Test IA - Cohere</title>
</head>
<body>
  <h1>Tester l'IA (Cohere)</h1>
  <form id="chatForm">
    <input type="text" id="question" placeholder="Pose une question..." size="50">
    <button type="submit">Envoyer</button>
  </form>
  <pre id="response"></pre>

  <script>
    document.getElementById('chatForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const q = document.getElementById('question').value;
      const res = await fetch("ask_general.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "question=" + encodeURIComponent(q)
      });
      const data = await res.json();
      document.getElementById('response').textContent = data.answer;
    });
  </script>
</body>
</html>
