import React, { useEffect, useState } from "react";
import "./login.css";
import { useNavigate } from "react-router-dom"; // Import navigace
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

function Login() {
  const [loginData, setloginData] = React.useState([]);

  const getData = () => {
    fetch("https://acvisnovka.cz/admin/php/login.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        if (data) {
          setloginData(data);
        } else {
          console.error("Empty response from server");
        }
      })
      .catch((error) => console.error("GG eror:", error));
  };

  const sendTokenToDatabase = async () => {
    // Definuj token, který chceš uložit

    const response = await fetch("https://acvisnovka.cz/admin/php/token.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
    });

    if (response.ok) {
      const result = await response.text();
    } else {
      console.error("Chyba při komunikaci s serverem:", response.status);
    }
  };

  useEffect(() => {
    getData();
  }, []);

  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const navigate = useNavigate();

  const handleKeyDown = (event) => {
    if (event.key === "Enter") {
      handleLogin(); // Spustí funkci při stisknutí Enter
    }
  };

  const handleLogin = () => {
    // Akce při přihlášení (například ověření přihlašovacích údajů)
    if (loginData.some((test) => test.name === username && test.password === password)) {
      sendTokenToDatabase();
      toast.info("Přihlašovaní ...");
      setTimeout(() => {
        navigate("/admin/admin-panel");
      }, 1000); // 2 seconds delay
    } else {
      toast.error("Špatné přihlašovací údaje");
      setUsername("");
      setPassword("");
    }
  };

  return (
    <>
      <ToastContainer position="bottom-right" autoClose={500} hideProgressBar={false} newestOnTop={false} closeOnClick rtl={false} pauseOnFocusLoss draggable pauseOnHover theme="light" transition:Bounce />
      <div className="login">
        <h2>Přihlášení</h2>
        <input type="text" id="name" placeholder="Přihlašovací jméno" value={username} onChange={(e) => setUsername(e.target.value)} onKeyDown={handleKeyDown} />
        <input type="password" id="pass" placeholder="Heslo" value={password} onChange={(e) => setPassword(e.target.value)} onKeyDown={handleKeyDown} />
        <button onClick={handleLogin}>Přihlásit</button>
      </div>
    </>
  );
}

export default Login;
