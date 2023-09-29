import React from "react";
import {BrowserRouter} from "react-router-dom";
import Navigation from "./components/Navigation/Navigation";
import Sidebar from "./components/Sidebar/Sidebar";
import Footer from "./components/Footer/Footer.jsx";
import Content from "./components/Content/Content.jsx";

const APP_NAME = 'JDULib'
const App = () => {
    return (
        <BrowserRouter>
            <Navigation/>
            <Sidebar appName={APP_NAME} username={'John Doe'}/>
            <Content headerName={'Test'} breadcrumb={[
                {title: 'Test', url: ''},
                {title: 'Test', url: ''},
            ]}>
                <h1>Hello</h1>
            </Content>
            <Footer/>
        </BrowserRouter>
    )
}
export default App
