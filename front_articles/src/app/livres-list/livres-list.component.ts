import { Component, OnInit } from '@angular/core';
import { Livre } from '../models/livre';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-livres-list',
  templateUrl: './livres-list.component.html',
  styleUrl: './livres-list.component.css'
})
export class LivresListComponent implements OnInit {
  livres: Livre[] = [];

  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.apiService.getLivres().subscribe((data: Livre[]) => {
      this.livres = data;
    });
  }
}
