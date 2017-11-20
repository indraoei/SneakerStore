import { Component, OnInit } from '@angular/core';
import { Http, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import { Router } from "@angular/router";

@Component({
  selector: 'app-sign-in',
  templateUrl: './sign-in.component.html',
  styleUrls: ['./sign-in.component.css']
})
export class SignInComponent implements OnInit {

  email: string;
  password: string;

  constructor(private http: Http, private router: Router) { }

  ngOnInit() {
  }

  SignIn(){
    var body = JSON.stringify({
      "email" : this.email,
      "password" : this.password
    });
    var hdr = new Headers({ "Content-Type" : "application/json" });
    var options = new RequestOptions({ headers : hdr })

    this.http.post('http://localhost:8000/api/user/signin', body, options)
    .subscribe(
      result => {
        // sessionStorage.setItem("token", result.json().token); //--> disimpan sememntara, browser ditutup udh ga tersimpan
        sessionStorage.setItem("token", result.json().token); //--> disimpan di cache browser, browser ditutup masih tersimpan
        this.router.navigate(['/']);

        console.log(result.json().token);
      },
      error => {
        console.log(error);
      }
    );
  }
  redirectToProduct(){
    this.router.navigate(['/product'])
  }

}
