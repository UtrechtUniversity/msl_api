import { Evented } from "leaflet";

class EventBus extends Evented { }

export const bus = new EventBus();