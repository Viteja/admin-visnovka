import { useState } from "react";

import "./navbar.css";

function Navbar() {
  return (
    <>
      <nav>
        <div className="navbar">
          <div className="navlogo">
            <img src="/admin/img/ac_logo.png" alt="" />
          </div>
          <div className="line"></div>
          <div className="navmenu">
            <ul className="menu">
              <li>
                <a href="https://designjj-test.eu/">
                  <i className="fa-solid fa-house"></i>
                </a>
              </li>
            </ul>
            <ul className="menu">
              <li>
                <a href="https://designjj-test.eu/admin/php/logout.php">
                  <i className="fa-solid fa-right-from-bracket"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </>
  );
}

export default Navbar;
