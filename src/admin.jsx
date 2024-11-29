import React, { useEffect, useState } from "react";
import "./admin.css";
import Navbar from "./components/navbar";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import axios from "axios";

function Admin() {
  const [creditals, setCreditals] = useState({ name: "", desc: "", text: "" });
  const [list, setList] = useState([]);
  const [editMode, setEditMode] = useState(false); // Stav pro editaci projektu

  // Funkce pro načítání dat z backendu
  const loadData = () => {
    fetch("https://designjj-test.eu/admin/php/getprojekt.php", {
      method: "POST",
    })
      .then((res) => res.json())
      .then((data) => setList(data))
      .catch((err) => console.error("Chyba při načítání dat:", err));
  };

  useEffect(() => {
    loadData(); // Načítání dat při načtení komponenty
  }, []);

  useEffect(() => {
    update(); // Načítání dat při načtení komponenty
  }, []);

  // Změna hodnot ve formuláři
  const _changeCreditals = (e) => {
    setCreditals({ ...creditals, [e.target.name]: e.target.value });
  };

  // Funkce pro aktualizaci dat
  const update = () => {
    fetch("https://designjj-test.eu/admin/php/projekt.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        type: "update",
        name: creditals.name, // Název projektu jako identifikátor
        desc: creditals.desc,
        text: creditals.text,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          toast.success("Hodnota byla úspěšně aktualizována");
          loadData(); // Aktualizace seznamu projektů
          setEditMode(false); // Ukončení editačního režimu
        } else {
          toast.error(data.message || "Chyba při aktualizaci dat");
        }
      })
      .catch((err) => {
        toast.error("Chyba při odesílání dat: " + err.message);
      });
  };

  // Funkce pro odstranění projektu
  const remove = (id) => {
    fetch("https://designjj-test.eu/admin/php/projekt.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        type: "remove",
        id: id, // Id projektu, který chcete odstranit
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          toast.success("Projekt byl úspěšně odstraněn");
          loadData(); // Obnovíme seznam projektů
        } else {
          toast.error("Chyba při odstraňování projektu");
        }
      })
      .catch((err) => {
        toast.error("Chyba při odesílání dat: " + err.message);
      });
  };

  // Funkce pro přechod do editačního režimu
  const editRow = (item) => {
    setCreditals(item); // Předání hodnot do editačního formuláře
    setEditMode(true);
  };

  return (
    <>
      <ToastContainer position="bottom-right" autoClose={5000} hideProgressBar={false} newestOnTop={false} closeOnClick rtl={false} pauseOnFocusLoss draggable pauseOnHover theme="light" />
      <Navbar />
      <div className="main">
        <div className="container">
          <div className="title">
            <h1>Admin panel</h1>
          </div>
          <h2>Projekty</h2>

          {/* Formulář pro přidání nebo úpravu projektu */}
          {editMode ? (
            <div className="projekt">
              <input type="text" name="name" placeholder="Název" value={creditals.name} onChange={_changeCreditals} />
              <input type="text" name="desc" placeholder="Podnadpis" value={creditals.desc} onChange={_changeCreditals} />
              <input type="text" name="text" placeholder="Popis" value={creditals.text} onChange={_changeCreditals} />
              <button onClick={update}>Odeslat</button>
            </div>
          ) : (
            <div>
              <p>
                <strong>Název:</strong> {creditals.name}
              </p>
              <p>
                <strong>Podnadpis:</strong> {creditals.desc}
              </p>
              <p>
                <strong>Popis:</strong> {creditals.text}
              </p>
            </div>
          )}

          {/* Seznam projektů */}
          <table className="projekt-list">
            <tbody>
              <tr className="header">
                <th>Název</th>
                <th>Podnadpis</th>
                <th>Popis</th>
                <th>Akce</th>
              </tr>
              {list.map((item) => (
                <tr key={item.id}>
                  <td>{item.name}</td>
                  <td>{item.desc}</td>
                  <td>{item.text}</td>
                  <td>
                    <button onClick={() => editRow(item)}>Upravit</button>
                  </td>
                  <td>
                    <button onClick={() => remove(item.id)}>
                      <i className="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </>
  );
}

export default Admin;
