import React, { useEffect, useState } from "react";
import "./admin.css";
import Navbar from "./components/navbar";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import axios from "axios";

function Admin() {
  const [creditals, setCreditals] = useState({ name: "", desc: "", text: "" });
  const [creditals2, setCreditals2] = useState({ text: "" });

  const [list, setList] = useState([]);
  const [list2, setList2] = useState([]);

  const [editMode, setEditMode] = useState(false); // Stav pro editaci projektu
  const [editMode2, setEditMode2] = useState(false); // Stav pro editaci projektu

  const [editId, setEditId] = useState(null); // ID projektu pro editaci
  const [editId2, setEditId2] = useState(null); // ID projektu pro editaci

  // ------------------------ TOKENY -------------------------

  useEffect(() => {
    verifyToken();
  }, []);

  const verifyToken = () => {
    fetch("https://acvisnovka.cz/admin/php/verify-token.php") // Nahraď cestou k PHP skriptu
      .then((response) => {
        if (!response.ok) {
          throw new Error("Chyba při načítání dat z PHP");
        }
        return response.json(); // Očekáváme JSON odpověď
      })
      .then((data) => {
        // Debugging - výpis tokenů pro ladění
        console.log("Response data:", data);

        const sessionToken = data.sessionToken; // Token ze session
        const databaseToken = data.databaseToken; // Token z databáze

        console.log("Session token:", sessionToken);
        console.log("Database token:", databaseToken);

        // Kontrola, jestli jsou tokeny správně načteny
        if (sessionToken === undefined || databaseToken === undefined) {
          console.error("Jedna nebo obě hodnoty tokenu chybí.");
          return;
        }

        // Porovnání tokenů
        if (sessionToken === databaseToken) {
          // Tokeny se shodují – přesměrování na admin-panel
          toast.success("Přihlášení proběhlo úspěšně");
        } else {
          // Tokeny se neshodují – zůstaň na /admin/
          console.log("Tokeny se neshodují.");
          window.location.href = "/admin/";
        }
      })
      .catch((error) => {
        console.error("Chyba:", error);
        alert("Session vypršela.");
      });
  };

  // ------------------------     PROJEKTY     ------------------------

  // Funkce pro načítání dat z backendu
  const loadData = () => {
    fetch("https://acvisnovka.cz/admin/php/getprojekt.php", {
      method: "POST",
    })
      .then((res) => res.json())
      .then((data) => setList(data))
      .catch((err) => console.error("Chyba při načítání dat:", err));
  };

  useEffect(() => {
    loadData(); // Načítání dat při načtení komponenty
  }, []);

  // Změna hodnot ve formuláři
  const _changeCreditals = (e) => {
    setCreditals({ ...creditals, [e.target.name]: e.target.value });
  };

  // Funkce pro aktualizaci dat
  const update = (id) => {
    if (!creditals.name || !creditals.desc || !creditals.text) {
      toast.error("Všechna pole musí být vyplněná!");
      return;
    }

    fetch("https://acvisnovka.cz/admin/php/projekt.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        type: "update",
        id: editId, // ID projektu, který chcete aktualizovat
        name: creditals.name, // Název projektu
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
          setEditId(null); // Reset ID po úspěšné aktualizaci
        } else {
          toast.error(data.message || "Chyba při aktualizaci dat");
        }
      })
      .catch((err) => {
        toast.error("Chyba při odesílání dat: " + err.message);
      });
  };

  const editRow = (item) => {
    setCreditals(item);
    setEditMode(true);
    setEditId(item.id);
  };

  const remove = (id) => {
    if (confirm("Opravdu chcete odstranit tento projekt?")) {
      fetch("https://acvisnovka.cz/admin/php/projekt.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          type: "remove",
          id: id,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            toast.success("Projekt byl úspěšně odstraněn");
            loadData();
          } else {
            toast.error("Chyba při odstraňování projektu");
          }
        })
        .catch((err) => {
          toast.error("Chyba při odesílání dat: " + err.message);
        });
    }
  };

  // ------------------------     IKONY     ------------------------

  const loadIcons = () => {
    fetch("https://acvisnovka.cz/admin/php/geticons.php", {
      method: "POST",
    })
      .then((res) => res.json())
      .then((data) => setList2(data))
      .catch((err) => console.error("Chyba při načítání dat:", err));
  };

  useEffect(() => {
    loadIcons();
  }, []);

  const _changeCreditals2 = (e) => {
    setCreditals2({ ...creditals, [e.target.name]: e.target.value });
  };

  const update2 = (id) => {
    if (!creditals2.text) {
      toast.error("Všechna pole musí být vyplněná!");
      return;
    }

    fetch("https://acvisnovka.cz/admin/php/icons.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        type: "update",
        id: editId2,
        text: creditals2.text,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          toast.success("Hodnota byla úspěšně aktualizována");
          loadIcons();
          setEditMode2(false);
          setEditId2(null);
        } else {
          toast.error(data.message || "Chyba při aktualizaci dat");
        }
      })
      .catch((err) => {
        toast.error("Chyba při odesílání dat: " + err.message);
      });
  };

  const editRow2 = (item2) => {
    setCreditals2(item2);
    setEditMode2(true);
    setEditId2(item2.id);
  };

  const remove2 = (id) => {
    if (confirm("Opravdu chcete odstranit tento text?")) {
      fetch("https://acvisnovka.cz/admin/php/icons.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          type: "remove",
          id: id,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            toast.success("Projekt byl úspěšně odstraněn");
            loadData();
          } else {
            toast.error("Chyba při odstraňování projektu");
          }
        })
        .catch((err) => {
          toast.error("Chyba při odesílání dat: " + err.message);
        });
    }
  };

  return (
    <>
      <Navbar />
      <ToastContainer position="bottom-right" autoClose={5000} hideProgressBar={false} newestOnTop={false} closeOnClick rtl={false} pauseOnFocusLoss draggable pauseOnHover theme="light" />
      <div className="main">
        <div className="container">
          <div className="title">
            <h1>Projekty</h1>
          </div>
          {/*  ------------------------     PROJEKTY     ------------------------ */}
          {editMode ? (
            <div className="projekt">
              <input type="text" name="name" placeholder="Název" value={creditals.name} onChange={_changeCreditals} />
              <input type="text" name="desc" placeholder="Podnadpis" value={creditals.desc} onChange={_changeCreditals} />
              <textarea type="text" name="text" placeholder="Popis" value={creditals.text} onChange={_changeCreditals} />
              <button className="send" onClick={update}>
                <span>Uložit</span>
                <i className="fa-solid fa-floppy-disk"></i>
              </button>
            </div>
          ) : (
            <div></div>
          )}
          <table className="projekt-list">
            <tbody>
              <tr className="header">
                <th className="name">Název</th>
                <th className="desc">Podnadpis</th>
                <th className="text">Popis</th>
                <th className="btn">Akce</th>
              </tr>
              {list.map((item) => (
                <tr className="project-item" key={item.id}>
                  <td id="name">{item.name}</td>
                  <td id="desc">{item.desc}</td>
                  <td id="text">{item.text}</td>
                  <td className="btns">
                    <button className="edit" onClick={() => editRow(item)}>
                      <i className="fas fa-edit"></i>
                    </button>
                    <button className="btn2" onClick={() => remove(item.id)}>
                      <i className="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>

          {/* ------------------------     IKONY     ------------------------  */}
          <div className="title">
            <h1>Ikony</h1>
          </div>

          {editMode2 ? (
            <div className="projekt">
              <input type="text" name="text" placeholder="Popis" value={creditals2.text} onChange={_changeCreditals2} />
              <button className="send" onClick={update2}>
                <span>Uložit</span>
                <i className="fa-solid fa-floppy-disk"></i>
              </button>
            </div>
          ) : (
            <div></div>
          )}
          <table className="projekt-list">
            <tbody>
              <tr className="header">
                <th>Název</th>
                <th>Akce</th>
              </tr>
              {list2.map((item2) => (
                <tr className="project-item" key={item2.id}>
                  <td id="text2">{item2.text}</td>
                  <td className="btns">
                    <button className="edit" onClick={() => editRow2(item2)}>
                      <i className="fas fa-edit"></i>
                    </button>
                    <button className="btn2" onClick={() => remove2(item2.id)}>
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
