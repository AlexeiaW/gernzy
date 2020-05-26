import { Container, interfaces } from 'inversify';
import { TYPES } from '../types/types';
import { GernzyGraphqlService } from '../interfaces/graphqlService';
import { StoreProducts } from '../interfaces/products';
import { GraphqlService } from '../graphqlService';
import { Products } from '../products';
import { GernzySession } from '../interfaces/session';
import { SessionService } from '../session';

const GernzyContainer = new Container();
GernzyContainer.bind<StoreProducts>(TYPES.StoreProducts).to(Products);
GernzyContainer.bind<GernzyGraphqlService>(TYPES.GernzyGraphqlService).to(GraphqlService);
GernzyContainer.bind<GernzySession>(TYPES.GernzySession).to(SessionService);

export { GernzyContainer };
