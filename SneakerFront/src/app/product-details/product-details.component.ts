import { Component, OnInit } from '@angular/core';
import { Http, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-product-details',
  templateUrl: './product-details.component.html',
  styleUrls: ['./product-details.component.css']
})
export class ProductDetailsComponent implements OnInit {

  product_id: number = 0;
  product: object;
  loading: boolean = true;
 
  constructor(private route:ActivatedRoute, private http: Http) { }

  ngOnInit() {
    this.route.params.subscribe(
      params => {
        this.product_id = params ['product_id'];
        
        this.http.get('http://localhost:8000/api/product/getproductbyid?product_id=' + this.product_id)
        .subscribe(
          result => {
            this.product = result.json()
            this.loading = false;
            console.log(result.json());
          },
          error => {
            console.log(error);
          }
        )
      }
    )
  }

}
